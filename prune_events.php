<?php
/* Author: Lukas Oertel git@luoe.dev
 *
 * Deletes old events from EEI, this script is called by Github Actions weekly
 */

require_once('config.php');
require_once('event_data.php');


$ALL_FILES = scandir($fp);

// get all filepaths (for detecting orphans)
$event_filepaths = array();
foreach ($events as &$event_id) {
    array_push($event_filepaths, $event_id['path']);
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
    foreach ($orphans as $file) {
        if (unlink($file)) {
            echo ("deleted orphan " . $file . PHP_EOL);
            array_push($deleted_events, $file);
        } else {
            http_response_code(500);
            exit("File could not be deleted. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
        }
    }
}

foreach ($events as &$event_id) {
    // delete list of participants if event was more than two weeks ago
    if (file_exists($event_id['path']) && time() >= ($event_id["uts"] + (86400 * 14))) {
        if (unlink($event_id["path"])) {
            echo ("deleted " . $event_id['path'] . PHP_EOL);
            array_push($deleted_events, $event_id['path']);
        } else {
            http_response_code(500);
            exit("File could not be deleted. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
        }
    }
}

echo "deleted " . count($deleted_events) . " events in total" . PHP_EOL;

exit;
?>