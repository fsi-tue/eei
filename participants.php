<?php
# Sends a participant list mail via PHPMailer
require_once("config.php");
require_once("utils.php");
require_once("localisation/localizer.php");
$localizer = new Localizer();
// import event_data after localizer, because event_data uses $localizer
require_once("event_data.php");

// start session
session_start();

global $FILE_REVISION, $events;

/**
 * Send a participant list mail via PHPMailer.
 *
 * @param $E         array The event object.
 *
 * @return bool Whether the mail was sent successfully.
 */
function sendParticipantListMail(array $E): bool
{
    $recipient = $E["responsible"];
    if (empty($recipient)) {
        return FALSE;
    }

    $participants = getParticipants($E);
    $subject = "Teilnehmerliste für {$E["name"]}";
    $msg = "Teilnehmerliste für {$E["name"]}:\n\n";

    foreach ($participants as $participant) {
        $msg .= "{$participant["name"]} ({$participant["mail"]}) {$participants["misc"]}\n";
    }

    return sendMailViaPHPMailer($recipient, $subject, $msg);
}

/**
 * Returns the participants of an event as an array of arrays.
 *
 * @param $E array event object.
 *
 * @return array<array<string>> The participants of the event.
 */
function getParticipants($E): array
{
    $filepath = $E["path"];
    $file = fopen($filepath, "r");
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
                foreach ($events as $e) {
                    if ($e["active"]) {
                        ?>
                        <option value="<?= $e["link"] ?>"><?= $e["name"] ?></option>
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
        // check if the POST request is valid
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
                <div class="block error">Beim Versenden der Teilnehmerliste ist ein Fehler aufgetreten.</div>
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
