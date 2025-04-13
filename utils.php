<?php
require_once 'config.php';
require_once 'i18n/i18n.php';
require_once 'email.php';
require_once 'calendar.php';
require_once 'event_type.php';

global $i18n, $fp;
$CSV_OPTIONS = array(
	'separator' => ',',
	'enclosure' => '"',
	'escape' => '\\',
);

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

function getRemoteAddr(): string
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'];
	$baseURL = "{$protocol}://{$host}";
	return $baseURL;
}

function createEeiRegistrationFolder(): void
{
	global $fp;

	if (!file_exists($fp)) {
		if (mkdir($fp, 0777, true)) {
			echo "Created folder $fp\n";
		} else {
			http_response_code(500);
			exit("Folder could not be created. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
		}
	}
}

function replaceFirstOccurence($searchStr, $replacementStr, $sourceStr)
{
	return (false !== ($pos = strpos($sourceStr, $searchStr))) ? substr_replace($sourceStr, $replacementStr, $pos, strlen($searchStr)) : $sourceStr;
}

function writeHeader($file, Event $event): void
{
	global $CSV_OPTIONS;
	clearstatcache();
	if (!filesize($event->csvPath)) {
		if ($event->form['course_required'])
			$headers = array("name", "mail", "studiengang", "abschluss", "semester");
		else
			$headers = array("name", "mail");
		if ($event->form['food'])
			$headers[] = "essen";
		if ($event->name === "Ersti WE")
			$headers[] = "fruehstueck";
		if ($event->form['gender'])
			$headers[] = "geschlecht";		
		fputcsv($file, $headers, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape']);
	}
}

# Get the address of the server
function getAddress(): string
{
	return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
}

# Checks if the given email is a valid UT email address
function validateEmail($email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) && str_ends_with($email, '.uni-tuebingen.de');
}

# Sends a registration mail via PHPMailer
function sendRegistrationMail(string $recipient, string $registration_id, Event $event): void
{
	global $i18n;

	$subject = $i18n->translate('email_registration_subject',
		array('EVENT_NAME' => $event->name,
			'DATE' => $event->getEventDateString(array(
				'compact' => true,
				'no_time' => true
			))));
	$deleteRegistrationLink = getAddress() . "/event.php?e={$event->link}&r=$registration_id&lang={$i18n->getLanguage()}";
	$deleteRegistrationHTML = "<a href='$deleteRegistrationLink'>{$i18n->translate('unsubscribe')}</a>";
	$msg = $i18n->translate('email_registration_body',
		array('EVENT_NAME' => $event->name,
			'DATE' => $event->getEventDateString(),
			'DELETE_REGISTRATION_LINK' => $deleteRegistrationHTML,
			'SENDER_NAME' => getEnvVar('SENDER_NAME')));
	$generator = new ICSGenerator($event);
	$ics = $generator->generateICS();
	sendMailViaPHPMailer($recipient, $subject, $msg, $ics, 'event.ics');
}

# Sends a registration deleted mail via PHPMailer
function sendRegistrationDeletedMail($recipient, Event $event): void
{
	global $i18n;
	$subject = $i18n->translate('email_unsubscribe_subject', array('EVENT_NAME' => $event->name));
	$msg = $i18n->translate('email_unsubscribe_body', array('EVENT_NAME' => $event->name, 'SENDER_NAME' => getEnvVar('SENDER_NAME')));
	sendMailViaPHPMailer($recipient, $subject, $msg);
}


# Generates an ID
# It is used to identify the registration
function generateRegistrationIDFromData($data, Event $event): string
{
	// Use only “static” values from the event
	return hash('sha256', implode("", [...$data, $event->link, $event->csvPath, $event->isUpcoming()]));
}

# Deletes a registration
function deleteRegistration($registration_id, Event $event): array
{
	global $i18n, $CSV_OPTIONS;
	$filepath = $event->csvPath;
	// Check if csv file exists
	if (!file_exists($filepath)) {
		return array(false, $i18n->translate('unsubscribed_error'));
	}
	$file = fopen($filepath, "r");

	$data = array();
	$success = false;
	$deletedLine = NULL;

	while (($line = fgetcsv($file, null, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape'])) !== false) {
		// if the registration hash matches the one we're looking for, skip it
		if (generateRegistrationIDFromData($line, $event) !== $registration_id) {
			// if it's not, add it to the new data array
			$data[] = $line;
		} else {
			$deletedLine = $line;
			$success = true;
		}
	}

	// close the file and open it for writing
	fclose($file);
	$file = fopen($filepath, 'w');

	// loop through the modified data array and write each line to the file
	foreach ($data as $line) {
		fputcsv($file, $line, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape']);
	}
	fclose($file);

	if ($success) {
		sendRegistrationDeletedMail($deletedLine[1], $event);
		return array(true, $i18n->translate('unsubscribed_success'));
	}
	return array(false, $i18n->translate('unsubscribed_error'));
}

# Processes a registration
function register(Event $event): array
{
	global $i18n, $CSV_OPTIONS;

	// Check if csv file exists
	if (!file_exists($event->csvPath)) {
		// Create the file if it doesn't exist
		$file = fopen($event->csvPath, "w");
		if ($file === false) {
			return array(false, $i18n['form_error']);
		}
		fclose($file);
	}

	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
	$mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);

	if ($event->form['course_required']) {
		$studiengang = filter_input(INPUT_POST, 'studiengang', FILTER_SANITIZE_ENCODED);
		$abschluss = filter_input(INPUT_POST, 'abschluss', FILTER_SANITIZE_ENCODED);
		$semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_ENCODED);
	}
	if ($event->form['food'])
		$essen = filter_input(INPUT_POST, 'essen', FILTER_SANITIZE_ENCODED);
	if ($event->name === "Ersti WE")
		$fruehstueck = filter_input(INPUT_POST, 'fruehstueck', FILTER_SANITIZE_ENCODED);
	if ($event->form['gender'])
		$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_ENCODED);

	if (empty($mail) || empty($name) || ($event->form['course_required'] && (empty($studiengang) || empty($semester) || empty($abschluss)))) {
		return array(false, $i18n['missing_data']);
	}

	// already registered
	if (file_exists($event->csvPath) && str_contains(file_get_contents($event->csvPath), $mail)) {
		return array(false, $i18n['already_registered']);
	}

	$data = array();

	$data[] = $name;
	$data[] = $mail;

	if ($event->form['course_required']) {
		$data[] = $studiengang;
		$data[] = $abschluss;
		$data[] = $semester;
	}
	if ($event->form['food']) {
		$data[] = $essen;
	}
	if ($event->name === "Ersti WE") {
		$data[] = $fruehstueck;
	}
	if ($event->form['gender']) {
		$data[] = $gender;
	}

	// open the file in append mode
	$file = fopen($event->csvPath, "a");
	if ($file === false) {
		return array(false, $i18n['form_error']);
	}

	// add CSV headers if file doesn't exist yet
	// check if file is empty, because we can't check if it exists because it was opened with fopen()
	writeHeader($file, $event);
	// use fputcsvRetVal to check if the write was successful
	$fputcsvRetVal = fputcsv($file, $data, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape']);
	fclose($file);

	if ($fputcsvRetVal !== false) {
		// Generate registration hash and send mail
		sendRegistrationMail($mail, generateRegistrationIDFromData($data, $event), $event);

		return array(true, $i18n['form_success']);
	}
	return array(false, $i18n['form_error']);
}
