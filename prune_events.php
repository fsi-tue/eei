<?php
/* Author: Lukas Oertel git@luoe.dev
 *
 * Deletes old events from EEI, this script is called by Github Actions weekly
 */

require_once 'config.php';
require_once 'event_type.php';

global $events, $fp;

$ALL_FILES = scandir($fp);

// get all filepaths (for detecting orphans)
$event_filepaths = array();
foreach ($events as /* @var Event $event */
		 $event) {
	$event_filepaths[] = $event->csvPath;
}

$deleted_events = array();

// get all files in $fp
// i.e. for detecting orphans not in events.php
$ALL_FILES = array_diff(scandir($fp), array('.', '..'));
foreach ($ALL_FILES as &$file) {
	$file = $fp . $file;
}

if ($ALL_FILES != $event_filepaths) {
	// remove orphans, i.e. files that are not in events_data.php
	$orphans = array_diff(array_merge($ALL_FILES, $event_filepaths), $event_filepaths);
	unset($file);
	foreach ($orphans as $file) {
		if (unlink($file)) {
			echo("deleted orphan " . $file . PHP_EOL);
			$deleted_events[] = $file;
		} else {
			http_response_code(500);
			exit("File could not be deleted. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
		}
	}
}

foreach ($events as /* @var Event $event */
		 $event) {
	// delete list of participants if event was more than two weeks ago
	if (file_exists($event->csvPath) && time() >= ($event->getEventStartUTS() + (86400 * 14))) {
		if (unlink($event["path"])) {
			echo("deleted " . $event->csvPath . PHP_EOL);
			$deleted_events[] = $event->csvPath;
		} else {
			http_response_code(500);
			exit("File could not be deleted. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
		}
	}
}

echo "deleted " . count($deleted_events) . " events in total" . PHP_EOL;

exit;
