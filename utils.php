<?php
require_once('config.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();
require_once('email.php');

# Loads the environment variables from the .env file
# This is a modified version of the function from:
# https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i
#
# It does not support every feature, but it is sufficient for our needs.
function loadEnv($path): void
{
    // Path is not readable
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        // Skip special lines
        if (empty($line) || !str_contains($line, '=') || str_starts_with(trim($line), '#')) {
            continue;
        }

        // Parse the line
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

# Returns the value of an environment variable
function getEnvVar($key, $default = NULL): mixed
{
    // use getenv() if possible
    return getenv($key) ?: $default;
}

# Checks if the environment is a local environment
function isLocalhost($whitelist = ['127.0.0.1', '::1']): bool
{
    return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
}

function replaceFirstOccurence($searchStr, $replacementStr, $sourceStr)
{
    return (FALSE !== ($pos = strpos($sourceStr, $searchStr))) ? substr_replace($sourceStr, $replacementStr, $pos, strlen($searchStr)) : $sourceStr;
}

# Echos the number of remaining spots for an event e
function getNumberOfRemainingSpots($E): int
{
    if ($E['max_participants']) {
        $filepath = $E['path'];
        $HEADER_LINE_COUNT = 1;
        if (file_exists($filepath)) {
            $file = file($filepath, FILE_SKIP_EMPTY_LINES);
            $spots = $E['max_participants'] - (count($file) - $HEADER_LINE_COUNT);
            if ($spots <= 0) {
                $spots = 0;
            }
            return $spots;
        } else {
            return $E['max_participants'];
        }
    }
    return 0;
}

function writeHeader($file, $E): void
{
    clearstatcache();
    if (!filesize($E['path'])) {
        if ($E['course_required'])
            $headers = array("name", "mail", "studiengang", "abschluss", "semester");
        else
            $headers = array("name", "mail");
        if ($E['food'])
            $headers[] = "essen";
        if ($E['name'] === "Ersti WE")
            $headers[] = "fruehstueck";
        fputcsv($file, $headers);
    }
}

# Get the address of the server
function getAddress(): string
{
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
}

# Sends a registration mail via PHPMailer
function sendRegistrationMail(string $recipient, string $registration_id, array $E): void
{
    global $localizer;
    $subject = $localizer->translate('email_registration_subject', array('EVENT_NAME' => $E['name']));
    $deleteRegistrationLink = getAddress() . "/event.php?e={$E['link']}&r=$registration_id&lang={$localizer->getLang()}";
    $deleteRegistrationHTML = "<a href='$deleteRegistrationLink'>{$localizer->translate('unsubscribe')}</a>";
    $msg = $localizer->translate('email_registration_body', array('EVENT_NAME' => $E['name'], 'DELETE_REGISTRATION_LINK' => $deleteRegistrationHTML, 'SENDER_NAME' => getEnvVar('SENDER_NAME')));
    sendMailViaPHPMailer($recipient, $subject, $msg);
}

# Sends a registration deleted mail via PHPMailer
function sendRegistrationDeletedMail($recipient, $E): void
{
    global $localizer;
    $subject = $localizer->translate('email_unsubscribe_subject', array('EVENT_NAME' => $E['name']));
    $msg = $localizer->translate('email_unsubscribe_body', array('EVENT_NAME' => $E['name'], 'SENDER_NAME' => getEnvVar('SENDER_NAME')));
    sendMailViaPHPMailer($recipient, $subject, $msg);
}


# Generates an ID
# It is used to identify the registration
function generateRegistrationIDFromData($data, $E): string
{
    // Use only “static” values from the event
    return hash('sha256', implode("", [...$data, $E["link"], $E["path"], $E["active"]]));
}

# Deletes a registration
function deleteRegistration($registration_id, $E): void
{
    global $localizer;
    $filepath = $E["path"];
    $file = fopen($filepath, "r");
    $data = array();
    $success = FALSE;
    $deletedLine = NULL;

    while (($line = fgetcsv($file)) !== FALSE) {
        // if the registration hash matches the one we're looking for, skip it
        if (generateRegistrationIDFromData($line, $E) !== $registration_id) {
            // if it's not, add it to the new data array
            $data[] = $line;
        } else {
            $deletedLine = $line;
            $success = TRUE;
        }
    }

    // close the file and open it for writing
    fclose($file);
    $file = fopen($filepath, 'w');

    // loop through the modified data array and write each line to the file
    foreach ($data as $line) {
        fputcsv($file, $line);
    }
    fclose($file);

    if ($success) {
        echo "<div class='block success'>{$localizer->translate('unsubscribed_success')}</div>";
        sendRegistrationDeletedMail($deletedLine[1], $E);
    } else {
        echo "<div class='block error'>{$localizer->translate('unsubscribed_error')}</div>";
    }
}

# Shows the form to delete a registration
function showDeleteRegistration($registration_id, $E): void
{
    global $localizer;
    ?>
    <form action="event.php?e=<?= $E['link'] ?>&r=<?= $registration_id ?>&lang=<?= $localizer->getLang() ?>"
          method="post">
        <div><?= $localizer->translate('unsubscribe_text') ?></div>
        <input type="hidden" name="registration_id" value="<?= $registration_id ?>">
        <input type="submit" name="delete_registration" value="<?= $localizer->translate('unsubscribe') ?>">
    </form>
    <?php
}

# Processes a registration
function register($E): void
{
    global $localizer, $CONFIG_CONTACT;

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);

    if ($E['course_required']) {
        $studiengang = filter_input(INPUT_POST, 'studiengang', FILTER_SANITIZE_ENCODED);
        $abschluss = filter_input(INPUT_POST, 'abschluss', FILTER_SANITIZE_ENCODED);
        $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_ENCODED);
    }
    if ($E['food'])
        $essen = filter_input(INPUT_POST, 'essen', FILTER_SANITIZE_ENCODED);
    if ($E['name'] === "Ersti WE")
        $fruehstueck = filter_input(INPUT_POST, 'fruehstueck', FILTER_SANITIZE_ENCODED);

    if (empty($mail) || empty($name) || ($E['course_required'] && (empty($studiengang) || empty($semester) || empty($abschluss)))) {
        echo "<div class='block error'>{$localizer['missing_data']}</div>";
        return;
    }

    // already registered
    if (str_contains(file_get_contents($E['path']), $mail)) {
        echo "<div class='block error'>{$localizer['already_registered']}</div>";
        return;
    }

    $data = array();

    $data[] = $name;
    $data[] = $mail;

    if ($E['course_required']) {
        $data[] = $studiengang;
        $data[] = $abschluss;
        $data[] = $semester;
    }
    if ($E['food']) {
        $data[] = $essen;
    }
    if ($E['name'] === "Ersti WE")
        $data[] = $fruehstueck;

    $file = fopen($E['path'], "a");

    if ($file === FALSE) {
        echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere <a href='mailto:$CONFIG_CONTACT'>$CONFIG_CONTACT</a></div>";
        return;
    }

    // add CSV headers if file doesn't exist yet
    // check if file is empty, because we can't check if it exists because it was opened with fopen()
    writeHeader($file, $E);
    // use fputcsvRetVal to check if the write was successful
    $fputcsvRetVal = fputcsv($file, $data);
    fclose($file);

    if ($fputcsvRetVal !== FALSE) {
        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
        // Generate registration hash and send mail
        sendRegistrationMail($mail, generateRegistrationIDFromData($data, $E), $E);
    } else {
        echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere $CONFIG_CONTACT.</div>";
    }
}

function markPastEvent(array $E) :string 
{
    if (time() > $E['startUTS']) {
        return " past ";
    } else {
        return "";
    }
}

/**
 * Build a date and time string for the given start and end date.
 *
 * @param array $E        - event
 * @param array $options  - array of options
 *                        <ul>
 *                        <li>compact: show date in compact mode</li>
 *                        </ul>
 *
 * @return string
 */
function showDateAndTime(array $E, array $options = array()): string
{
    global $localizer;

    $startUTS = $E['startUTS'];
    $endUTS = $E['endUTS'] ?? NULL;
    $onTime = $E['onTime'] ?? TRUE;
    $compact = isset($options['compact']) && $options['compact'];
    $hasEndDate = $endUTS && $endUTS != $startUTS;

    if ($compact) {
        // compact mode
        // 1.1.2017
        // 1.1.2017 - 2.1.2017
        // 1.1.2017 ab 12:00 Uhr

        $dateAndTime = $localizer->getLang() == 'de' ? date('d.m.y', $startUTS) : date('y-m-d', $startUTS);
        if ($hasEndDate) {
            $dateAndTime = $dateAndTime . ' - ' . ($localizer->getLang() == 'de' ? date('d.m.y', $endUTS) : date('y-m-d', $endUTS));
        } else {
            $dateAndTime = $dateAndTime . '<br>' . date('H:i', $startUTS);
        }
    } else {
        // full date and time
        // 1.1.2017 um 12:00 Uhr
        // 1.1.2017 ab 12:00 Uhr
        // 1.1.2017 um 12:00 Uhr - 2.1.2017 um 12:00 Uhr

        if ($localizer->getLang() == 'de') {
            $dateAndTime = date('d.m.Y', $startUTS);
            $dateAndTime = $dateAndTime . ($onTime ? ' um ' : ' ab ') . date('H:i', $startUTS) . ' Uhr';
        } else {
            $dateAndTime = date('Y-m-d', $startUTS);
            $dateAndTime = $dateAndTime . ($onTime ? ' at ' : ' from ') . date('H:i', $startUTS);
        }

        if ($hasEndDate) {
            $dateAndTime = $dateAndTime . ' - ';
            $dateAndTime = $localizer->getLang() == 'de' ? $dateAndTime . date('d.m.Y', $endUTS) : $dateAndTime . date('Y-m-d', $endUTS);
        }
    }

    return $dateAndTime;
}

function showRegistration($E): void
{
    global $CONFIG_CONTACT, $localizer;

    if (time() < $E['start_of_registration']) {
        ?>
        <div class='block error'><?= $localizer['start_of_registration'] ?></div>
        <?php
        return;
    }

    if (time() >= $E['end_of_registration']) {
        ?>
        <div class='block error'><?= $localizer['end_of_registration'] ?></div>
        <?php
        return;
    }


    if ($E['cancelled']) {
        ?>
        <div class="block error">
            <?= $localizer->translate('event_cancelled', array('EVENT_NAME' => $E['name'], 'EMAIL_CONTACT' => $CONFIG_CONTACT)) ?>
        </div>
        <?php
    }

    if (!$E['active']) {
        // return if the maximum number of participants has been reached
        return;
    }

    if (getNumberOfRemainingSpots($E) == 0) {
        ?>
        <div class="block error">
            <?= $localizer['event_full'] ?>
        </div>
        <?php
        return;
    }
    ?>

    <form method="post" action="#">
        <label for="form-name">
            <?= $localizer['form_yourName'] ?>:<br>
            <input type="text" id="form-name" name="name" required size="30">
        </label>
        <br><br>

        <label for="form-mail">
            <?= $localizer['form_email'] ?>:<br>
            <input type="email" id="form-mail" name="mail" required size="30">
        </label>
        <br><br>
        <?php

        // Courses
        if ($E['course_required']) {
            echo $localizer['form_study_programme'] . ':<br>';
            $courses = [
                ['Informatik', 'form_cs'],
                ['Lehramt', 'form_cs_ed'],
                ['Bioinformatik', 'form_cs_bio'],
                ['Medizininformatik', 'form_cs_med'],
                ['Medieninformatik', 'form_cs_media'],
                ['Maschinelles Lernen', 'form_ml'],
                ['Kognitive Informatik', 'form_cog'],
                ['Nebenfach', 'form_subsidiary']
            ];
            foreach ($courses as $course) {
                ?>
                <label>
                    <input type="radio" class="form-studiengang" name="studiengang" value="<?= $course[0] ?>" required>
                    <?= $localizer[$course[1]] ?>
                </label>
                <br>
                <?php
            }
            ?>
            <br>

            <?php
            // Degree
            echo $localizer['form_degree'] . ':<br>';
            $degrees = ['Bachelor', 'Master'];
            foreach ($degrees as $degree) {
                ?>
                <label>
                    <input type="radio" class="form-abschluss" name="abschluss" value="<?= $degree ?>" required>
                    <?= $degree ?>
                </label>
                <br>
                <?php
            }
            ?>
            <br>

            <?php
            // Semester
            echo 'Semester: <br>';
            $semesters = ['1', '2', '3', $localizer['form_many']];
            foreach ($semesters as $semester) {
                ?>
                <label>
                    <input type="radio" class="form-semester" name="semester" value="<?= $semester ?>" required>
                    <?= $semester ?>
                </label>
                <br>
                <?php
            }
            ?>
            <br>

            <?php
        }

        // Food
        if ($E['food']) {
            echo $localizer['form_food'] . ':<br>';
            $food_preferences = [
                $localizer['form_food_no_preference'],
                $localizer['form_food_vegetarian'],
                $localizer['form_food_vegan'],
                $localizer['form_food_no_pork']
            ];
            foreach ($food_preferences as $preference) {
                ?>
                <label>
                    <input type="radio" class="form-essen" name="essen" value="<?= $preference ?>" required>
                    <?= $preference ?>
                </label>
                <br>
                <?php
            }
        }
        ?>
        <br>

        <?php
        // Breakfast
        if ($E['breakfast']) {
            echo $localizer['form_breakfast'] . ':<br>';
            $food_preferences = [
                $localizer['form_food_no_preference'],
                $localizer['form_food_sweet'],
                $localizer['form_food_salty'],
            ];
            foreach ($food_preferences as $preference) {
                ?>
                <label>
                    <input type="radio" class="form-fruehstueck" name="fruestueck" value="<?= $preference ?>" required>
                    <?= $preference ?>
                </label>
                <br>
                <?php
            }
        }
        ?>
        <input type="submit" value="<?= $localizer['send'] ?>" onclick="saveFormValues()">
    </form>
    <script type="text/javascript" src="js/saveFormValues.js"></script>
    <?php
}
