<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n, $events;
const ICS_MIME_TYPE = 'text/calendar';
const ICALENDAR_HEADER = "
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
";
const ICALENDAR_FOOTER = "END:VCALENDAR";
const FSI_ICAL_CLOUD_URL = 'https://www.fsi.uni-tuebingen.de/__calendarHelper/';

# Downloads the ICS file from the FSI cloud and saves it to the file system.
function downloadAndSaveFSICloudICS(): bool
{
	// Download the ICS file from the FSI cloud.
	$content = file_get_contents(FSI_ICAL_CLOUD_URL);
	if ($content === false) {
		return false;
	}

	// Save the ICS file to the file system.
	global $fp;
	$filename = "{$fp}fsi.ics";
	$file = fopen($filename, 'w');
	if ($file === false) {
		return false;
	}
	fwrite($file, $content);
	fclose($file);
	return true;
}

# Returns the ICS file for the given event.
function getICSForEvent(Event $event): string
{
	// Check if the ICS file for the event exists.
	global $fp;
	$filename = "{$fp}fsi.ics";
	if (!file_exists($filename) && !downloadAndSaveFSICloudICS()) {
		// If download fails, return empty string.
		return '';
	}

	// Read the ICS file.
	$fsi_cloud_calendar = file_get_contents($filename);
	if ($fsi_cloud_calendar === false) {
		return '';
	}

	// Split the ICS file into events.
	$fsi_events = explode('BEGIN:VEVENT', $fsi_cloud_calendar);

	// Search in the event DESCRIPTION for the event with the given event id
	// and return it.
	foreach ($fsi_events as $fsi_event) {
		// Find Event ID in the event and return the event
		// It does not matter where it is, but for the sake of cleanliness
		// it could be put in the description of the event
		if (str_contains($fsi_event, $event->link)) {
			return ICALENDAR_HEADER . "BEGIN:VEVENT" . $fsi_event . ICALENDAR_FOOTER;
		}
	}

	// If the event was not found, return empty string.
	return '';
}

# If event id and "ics" are not set, redirect to main page.
if (isset($_GET['e']) && isset($_GET['ics'])) {
	$event_id = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_ENCODED);
	# If event id is unknown, redirect to main page.
	if (!array_key_exists($event_id, $events)) {
		header('Location:/');
		die();
	}

	$E = $events[$event_id];
	$ics = getICSForEvent($E);

	if ($ics !== '') {
		header('Content-Type: ' . ICS_MIME_TYPE);
		header('Content-Disposition: attachment; filename="' . $E->name . '.ics"');
		echo $ics;
	}
}
