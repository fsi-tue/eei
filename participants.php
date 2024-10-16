<?php
# Sends a participant list mail via PHPMailer
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

// start session
session_start();

// global variables and configuration
global $i18n, $FILE_REVISION, $events;
const MAX_TIME_BETWEEN_EMAILS = 2 * 60 * 60; // 2 hours

/**
 * Send a participant list mail via PHPMailer.
 *
 * @param $event         Event The event object.
 *
 * @return string String containing the message to be printed to the user.
 */
function sendParticipantListMail(Event $event): string
{
	// get email addresses for event
	$metas_email_addresses = $event->metas;
	if (!canSendEmail($event) || !$metas_email_addresses) {
		return "Der letzte Mailversand liegt unter der Minimalzeit, bitte versuch es später erneut!";
	}

	$subject = "Teilnehmerliste für $event->name am " . $event->getEventDateString();
	// build message
	$msg = "$subject:<br><br>";
	$participants = getParticipants($event);
	foreach ($participants as $participant) {
		$msg .= "{$participant["name"]} (<a href='mailto:{$participant["mail"]}'>{$participant["mail"]}</a>) {$participant["misc"]}<br>";
	}

	// provide list of participants
	$msg .= "<br><br>Mail-Liste:<br><br>";
	$msg .= implode(',', array_map(function($participant) {
	    return $participant['mail'];
	}, $participants));

	foreach ($metas_email_addresses as $meta_email) {
		if (!validateEmail($meta_email)) {
			return "Eine der hinterlegten Mailadressen ist keine gültige Mailadresse!";
		} else if(!sendMailViaPHPMailer($meta_email, $subject, $msg)) {
			return "Der Mailversand hat nicht funktioniert!";
		}
	}

	// log the sending of the participant list mail
	logToAvoidEmailSpam($event, $metas_email_addresses);

	return "Die Teilnehmerliste wurde erfolgreich versendet.";
}

/**
 * Returns the participants of an event as an array of arrays.
 *
 * @param $event Event event object.
 *
 * @return array<array<string>> The participants of the event.
 */
function getParticipants(Event $event): array
{
	$filepath = $event->csvPath;
	if (!file_exists($filepath)) {
		return array();
	}
	$file = fopen($filepath, "r");
	$participants = array();

	while (($line = fgetcsv($file)) !== false) {
		$participants[] = $line;
	}

	fclose($file);

	return array_map(function ($participant) {
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
 * @param Event $event
 * @param array $metas
 * @return array
 */
function logToAvoidEmailSpam(Event $event, array $metas): array
{
	global $fp;
	$data = array();
	$data[] = $event->link;
	$data[] = date("Y-m-d H:i:s");
	$data[] = implode(", ", $metas);

	$file = fopen($fp . 'logs.csv', "a");
	fputcsv($file, $data);

	fclose($file);

	return $data;
}

/**
 * Checks if an email can be sent to the event responsible.
 *
 * @param Event $event The event object.
 *
 * @return bool Whether an email can be sent.
 */
function canSendEmail(Event $event): bool
{
	global $fp;

	if (!file_exists($fp . 'logs.csv')) {
		return true;
	}
	$file = fopen($fp . 'logs.csv', "r");
	if (!$file) {
		return true;
	}

	// Find the last time the list of participants was sent
	$lastTime = 0;
	while (($line = fgetcsv($file)) !== false) {
		if ($line[0] === $event->link) {
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

$filtered_events = array_filter($events, fn(Event $event) => $event->isUpcoming());

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <title><?= $i18n["title"] ?></title>
</head>
<body>
<div id="center">
    <div class="container">
		<?php
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			?>
            <!-- Form to send a participant list mail -->
            <form action="participants.php" method="post">
                <label for="event">Event:</label>
                <select name="event" id="event">
					<?php
					foreach ($filtered_events as /* @var Event $event */
							 $event) {
						if ($event->isUpcoming()) {
							?>
                            <option value="<?= $event->link ?>"><?= $event->name ?>
                                - <?= $event->getEventDateString() ?>
                                - <?= $event->link ?></option>
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

            <div class="container">
                <a href="index.php?lang=<?= $i18n->getLanguage() ?>">
                    <div class="link">
						<?= $i18n['back'] ?>
                    </div>
                </a>
            </div>
			<?php
		} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
		?>
        <div class="container">
			<?php

			// Check if the POST request is valid
			// This checks the token that was sent in a POST request to the page
			// to prevent that someone uses automated programs to spam the person.
			if (isset($_POST["send"]) &&
				isset($_POST["event"]) &&
				isset($_POST[$_SESSION["token_field"]]) &&
				$_POST[$_SESSION["token_field"]] === $_SESSION["token"]) {

				$link = filter_input(INPUT_POST, 'event', FILTER_SANITIZE_ENCODED);
				$event = $events[$link];
				$success = sendParticipantListMail($event);
				if ($success == "Die Teilnehmerliste wurde erfolgreich versendet.") {
					?>
                    <div class="text-block">Die Teilnehmerliste wurde erfolgreich versendet.</div>
					<?php
				} else {
					?>
                    <div class="text-block error"><?php echo $success; ?></div>
					<?php
				}
			} else {
				?>
                <div class="text-block error">Ungültiger Vorgang.</div>
				<?php
			}
			?>
            <div class="container">
                <a href="participants.php?lang=<?= $i18n->getLanguage() ?>">
                    <div class="link">
						<?= $i18n['back'] ?>
                    </div>
                </a>
            </div>
			<?php
			} ?>
        </div>
    </div>
</div>
</body>
</html>
