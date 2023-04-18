<?php
require_once('config.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();

# Loads the environment variables from the .env file
# https://dev.to/fadymr/php-create-your-own-php-dotenv-3k2i
function loadEnv($path) {
    // Path is not readable
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {

        // Skip comments
        if (strpos(trim($line), '#') === 0) {
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
function getEnvVar($key, $default = null) {
    // use getenv() if possible
    return getenv($key) ?: $default;
}

function replaceFirstOccurence($searchStr, $replacementStr, $sourceStr) {
        return (false !== ($pos = strpos($sourceStr, $searchStr))) ? substr_replace($sourceStr, $replacementStr, $pos, strlen($searchStr)) : $sourceStr;
}

# Echos the number of remaining spots for a event e
function getNumberOfRemainingSpots($E) {
    if($E['max_participants']) {
        $filepath = $E['path'];
        $HEADER_LINE_COUNT = 1;
        if(file_exists($filepath)) {
            $file = file( $filepath, FILE_SKIP_EMPTY_LINES);
            $spots = $E['max_participants'] - (count($file) - $HEADER_LINE_COUNT);
            if($spots <= 0) {
                $spots = 0;
            }
            return $spots;
        }
        else {
            return $E['max_participants'];
        }
    }
}

function writeHeader($file, $E) {
    clearstatcache();
    if(!filesize($E['path'])){
        if($E['course_required'])
            $headers = array("name", "mail", "studiengang", "abschluss", "semester");
        else
            $headers = array("name", "mail");
        if($E['food'])
            $headers[] = "essen";
        if($E['name'] === "Ersti WE")
            $headers[] = "fruehstueck";
        fputcsv($file, $headers);
    }
}

function sendMail($recipient, $E) {
    global $SENDER_NAME, $SENDER_EMAIL;
    $subject = "Registrierung zu {$E['name']}";
    $msg = "Du hast dich erfolgreich zu {$E['name']} angemeldet.\n";
    $headers = "From:" . getEnvVar('SENDER_NAME') . " <" . getEnvVar('SENDER_EMAIL') . ">";
    mail($recipient, $subject, $msg, $headers);
}

# Processes a registration
function register($E){
    global $localizer, $CONFIG_CONTACT;

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);

    if($E['course_required']){
        $studiengang = filter_input(INPUT_POST, 'studiengang', FILTER_SANITIZE_ENCODED);
        $abschluss = filter_input(INPUT_POST, 'abschluss', FILTER_SANITIZE_ENCODED);
        $semester =filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_ENCODED);
    }
    if($E['food'])
        $essen = filter_input(INPUT_POST, 'essen', FILTER_SANITIZE_ENCODED);
    if($E['name'] === "Ersti WE")
        $fruehstueck = filter_input(INPUT_POST, 'fruehstueck', FILTER_SANITIZE_ENCODED);

    if(empty($mail) || empty($name) || ($E['course_required'] && (empty($studiengang) || empty($semester) || empty($abschluss)))){
        echo "<div class='block error'>{$localizer['missing_data']}</div>";
        return;
    }

    // already registered
    if(strpos(file_get_contents($E['path']), $mail) !== false){
        echo "<div class='block error'>{$localizer['already_registered']}</div>";
        return;
    }

    $data = array();

    array_push($data, $name);
    array_push($data, $mail);

    if($E['course_required']){
        array_push($data, $studiengang);
        array_push($data, $abschluss);
        array_push($data, $semester);
    }
    if($E['food']){
        array_push($data, $essen);
    }
    if($E['name'] === "Ersti WE")
        array_push($data, $fruehstueck);

    $file = fopen($E['path'], "a");

    if($file === false){
        echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
        return;
    }

    // add CSV headers if file doesn't exist yet
    // check if file is empty, because we can't check if it exists because it was opened with fopen()
    writeHeader($file, $E);
    fputcsv($file, $data);
    fclose($file);
    sendMail($mail, $E);

    echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
}

/**
 * Build a date and time string for the given start and end date.
 * @param $startUTS - unix timestamp
 * @param $endUTS - unix timestamp
 * @param $options - array of options
 *                 <ul>
 *                  <li>onTime: show time if start and end time are the same</li>
 *                  <li>compact: show date in compact mode</li>
 *                 </ul>
 *
 * @return string
 */
function showDateAndTime($startUTS, $endUTS = NULL, $options = array()) {
    global $localizer;

    $onTime = isset($options['onTime']) ? $options['onTime'] : true;
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
            $dateAndTime = $dateAndTime . '<br>'. date('H:i', $startUTS);
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

function showRegistration($E){
    global $CONFIG_CONTACT, $localizer;

    if(time() < $E['start_of_registration']) {
        echo "<div class='block error'>{$localizer['start_of_registration']}</div>";
        return;
    }

    if(time() >= $E['end_of_registration']) {
        echo "<div class='block error'>{$localizer['end_of_registration']}</div>";
        return;
    }


    if($E['cancelled']) {
        echo "<div class = 'block error'>{$localizer->translate('event_cancelled', array('EVENT_NAME' => $E['name'], 'EMAIL_CONTACT' => $CONFIG_CONTACT))}</div>";
    }

    if($E['active']){
        // return if the maximum number of participants has been reached

        if(getNumberOfRemainingSpots($E) == 0) {
            echo "<div class = 'block error'>{$localizer['event_full']}</div>";
            return;
        }
        echo '
            <form method = "post" action = "#">
                ' . $localizer['yourName'] . ': <br>
                <input type="text" id="form-name" name="name" required size="30"><br><br>

                ' . $localizer['email'] . ':<br>
                <input type="email" id="form-mail" name="mail" required size="30"><br><br>
        ';

        echo $E['course_required']?
                $localizer['study_programme'] . ':<br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Informatik" required> Informatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Lehramt"> Lehramt</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Bioinformatik"> Bioinformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Medizininformatik"> Medizininformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Medieninformatik"> Medieninformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Machine Learning"> Machine Learning</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Kognitionswissenschaft"> Kognitionswissenschaft</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Nebenfach" required> Nebenfach<br></label><br>

                Abschluss:<br>
                <label><input type="radio" class="form-abschluss" name="abschluss" value="Bachelor" required> Bachelor</label><br>
                <label><input type="radio" class="form-abschluss" name="abschluss" value="Master"> Master</label><br><br>

                Semester:<br>
                <label><input type="radio" class="form-semester" name="semester" value="1" required> 1</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="2"> 2</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="3"> 3</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="viele"> viele <label/><br>'
        : '';
        echo ($E['food']) ?
                '<br>Essen:<br>
                <label><input type="radio" class="form-essen" name="essen" value="keine Präferenzen" required> keine Präferenzen</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="Vegetarisch"> Vegetarisch</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="Vegan"> Vegan</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="kein Schwein"> kein Schwein</label><br>'
        : '';
        echo ($E['breakfast']) ?
                '<br>Frühstück:<br>
                <label><input type="radio" class="form-fruehstueck" name="fruehstueck" value="keine Präferenzen" required> keine Präferenzen</label> <br>
                <label><input type="radio" class="form-fruehstueck" name="fruehstueck" value="süß"> süß</label><br>
                <label><input type="radio" class="form-fruehstueck" name="fruehstueck" value="salzig"> salzig<label/><br>'
        : '';
        echo '
                <input type="submit" value="' . $localizer['send'] .'" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="../js/saveFormValues.js"></script>
        ';
    }
}
?>
