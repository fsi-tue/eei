<?php
# Sends a participant list mail via PHPMailer
require_once "config.php";
require_once "utils.php";
require_once "localisation/localizer.php";
$localizer = new Localizer();
// import event_data after localizer, because event_data uses $localizer
require_once "event_data.php";

// start session
session_start();

// global variables and configuration
global $FILE_REVISION, $events;
const MAX_TIME_BETWEEN_EMAILS = 12 * 60 * 60; // 12 hours

/**
 * Send a participant list mail via PHPMailer.
 *
 * @param $E         array The event object.
 *
 * @return bool Whether the mail was sent successfully.
 */
function sendParticipantListMail(array $E): bool
{
    if (!canSendEmail($E)) {
        return false;
    }

    // get email addresses for event
    $metas_email_addresses = $E["metas"];
    if (!$metas_email_addresses) {
        return false;
    }

    $subject = "Teilnehmerliste für {$E["name"]} am " . showDateAndTime($E);
    // build message
    $msg = "$subject:<br><br>";
    $participants = getParticipants($E);
    foreach ($participants as $participant) {
        $msg .= "{$participant["name"]} (<a href='mailto:{$participant["mail"]}'>{$participant["mail"]}</a>) {$participant["misc"]}<br>";
    }

    foreach ($metas_email_addresses as $meta_email) {
        if (!validateEmail($meta_email) || !sendMailViaPHPMailer($meta_email, $subject, $msg)) {
            return false;
        }
    }

    // log the sending of the participant list mail
    logToAvoidEmailSpam($E, $metas_email_addresses);

    return true;
}

/**
 * Returns the participants of an event as an array of arrays.
 *
 * @param $E array event object.
 *
 * @return array<array<string>> The participants of the event.
 */
function getParticipants(array $E): array
{
    $filepath = $E["path"];
    $file = fopen($filepath, "r");
    if (!$file) {
        return array();
    }
    $participants = array();

    while (($line = fgetcsv($file)) !== FALSE) {
        $participants[] = $line;
    }

    fclose($file);

    return array_map(function ($participant) use ($E) {
        $mappedParticipant = array();
        $mappedParticipant["name"] = $participant[0];
        $mappedParticipant["mail"] = $participant[1];
        $mappedParticipant["misc"] = implode(", ", array_slice($participant, 2));
        return $mappedParticipant;
    }, $participants);
}

/**
 * Log the sending of a participant list mail to avoid spamming the event responsible.
 *
 * @param array $E
 * @param array $responsible
 * @return array
 */
function logToAvoidEmailSpam(array $E, array $responsible): array
{
    global $fp;
    $data = array();
    $data[] = $E["link"];
    $data[] = date("Y-m-d H:i:s");
    $data[] = implode(", ", $responsible);

    $file = fopen($fp . 'logs.csv', "a");
    fputcsv($file, $data);

    fclose($file);

    return $data;
}

/**
 * Checks if an email can be sent to the event responsible.
 *
 * @param array $E The event object.
 *
 * @return bool Whether an email can be sent.
 */
function canSendEmail(array $E): bool
{
    global $fp;
    $file = fopen($fp . 'logs.csv', "r");
    if (!$file) {
        return true;
    }

    // Find the last time the list of participants was sent
    $lastTime = 0;
    while (($line = fgetcsv($file)) !== FALSE) {
        if ($line[0] === $E["link"]) {
            $lastTime = strtotime($line[1]);
        }
    }

    fclose($file);

    // If the last time the list of participants was sent is more than 24 hours ago, send the list of participants
    return time() - $lastTime > MAX_TIME_BETWEEN_EMAILS;
}


/**
 * Returns a random token.
 *
 * @return string
 */
function createRandomToken(): string
{
    try {
        return bin2hex(random_bytes(rand(28, 32)));
    } catch (Exception) {
        return "";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // set token and token field to prevent CSRF attacks on POST requests
    $_SESSION['token'] = createRandomToken();
    // token field is also randomly generated for another layer of security
    $_SESSION['token_field'] = createRandomToken();
}

$filtered_events = array_filter($events, fn(array $E) => time() < $E['startUTS'])

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/index.css<?= $FILE_REVISION; ?>">
    <title><?= $localizer["title"] ?></title>
</head>
<body>
<div id='center'>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        ?>
        <!-- Form to send a participant list mail -->
        <form action="participants.php" method="post">
            <label for="event">Event:</label>
            <select name="event" id="event">
                <?php
                foreach ($filtered_events as $E) {
                    if ($E["active"]) {
                        ?>
                        <option value="<?= $E["link"] ?>"><?= $E["name"] ?> - <?= $E["link"] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            <br>
            <input type="hidden" name="send" value="true">
            <input type="hidden" name="<?= $_SESSION['token_field'] ?>" value="<?= $_SESSION['token'] ?>">
            <br>
            <input type="submit" value="Liste senden">
        </form>
        <?php
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the POST request is valid
        // This checks the token that was sent in a POST request to the page
        // to prevent that someone uses automated programs to spam the person.
        if (isset($_POST["send"]) && isset($_POST["event"]) && isset($_POST[$_SESSION["token_field"]]) && $_POST[$_SESSION["token_field"]] === $_SESSION["token"]) {
            $event = filter_input(INPUT_POST, 'event', FILTER_SANITIZE_ENCODED);
            $E = $events[$event];
            $success = sendParticipantListMail($E);
            if ($success) {
                ?>
                <div class="block">Die Teilnehmerliste wurde erfolgreich versendet.</div>
                <?php
            } else {
                ?>
                <div class="block error">Das Versenden der Teilnehmerliste hat nicht funktioniert.</div>
                <?php
            }
        } else {
            ?>
            <div class="block error">Ungültiger Vorgang.</div>
            <?php
        }
        ?>
        <a href="participants.php">Zurück</a>
        <?php
    } ?>
</div>
</body>
</html>
