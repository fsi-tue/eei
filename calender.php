<?php
require_once('config.php');
require_once('utils.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();
// import event_data after localizer, because event_data uses $localizer
require_once('event_data.php');

global $events;
const ICS_MIME_TYPE = 'text/calendar';
const iCalenderHeader = "
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
";
const iCalenderFooter = "END:VCALENDAR";

function getICalEvent($E): string
{
    // Format date and time according to RFC 5545.
    $dtStart = date('Ymd\THis\Z', $E['startUTS']);
    // If end time is not set, use start time + 1 hour.
    $dtEnd = date('Ymd\THis\Z', $E['endUTS'] ?? $E['startUTS'] + 3600);
    // Strip HTML tags from summary, description and location.
    $summary = strip_tags($E['name']);
    $description = strip_tags($E['text']);
    $location = strip_tags($E['location']);

    return "
    BEGIN:VEVENT
UID:" . $E['link'] . "
DTSTAMP:" . $dtStart . "
DTSTART:" . $dtStart . "
DTEND:" . $dtEnd . "
SUMMARY:" . $summary . "
DESCRIPTION:" . $description . "
LOCATION:" . $location . "
END:VEVENT
";
}

function getICalenderFromEvent($E): string
{
    return iCalenderHeader . getICalEvent($E) . iCalenderFooter;
}

function getAllICalenders(): string
{
    global $events;
    $iCalenders = iCalenderHeader;
    foreach ($events as $E) {
        $iCalenders .= getICalEvent($E);
    }
    return $iCalenders . iCalenderFooter;
}

# If event id is not set, redirect to main page.
if (isset($_GET['e'])) {
    $event_id = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_ENCODED);
    # If event id is unknown, redirect to main page.
    if (!array_key_exists($event_id, $events)) {
        header('Location:/');
        die();
    }

    $E = $events[$event_id];
    header('Content-Type: ' . ICS_MIME_TYPE);
    header('Content-Disposition: attachment; filename="' . $E['name'] . '.ics"');
    echo getICalenderFromEvent($E);
} else {
    header('Content-Type: ' . ICS_MIME_TYPE);
    header('Content-Disposition: attachment; filename="all.ics"');
    echo getAllICalenders();
}