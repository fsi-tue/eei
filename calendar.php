<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

class ICSGenerator
{
    private const MIME_TYPE = 'text/calendar';
    private const LINE_ENDING = "\r\n";
    
    private array $events;
    
    public function __construct(array $events)
    {
        $this->events = $events;
    }
    
    public function generateICS(): string
    {
        $eventBlocks = array();
        foreach($this->events as $event) {
            if ($event->cancelled) {
                continue;
            }
            $eventBlocks[] = $this->generateEventBlock($event);
        }
        return implode(self::LINE_ENDING, [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//FSI//Calendar//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            implode(self::LINE_ENDING, $eventBlocks),
            'BEGIN:VTIMEZONE',
            'TZID:Europe/Berlin',
            'BEGIN:DAYLIGHT',
            'TZOFFSETFROM:+0100',
            'TZOFFSETTO:+0200',
            'TZNAME:CEST',
            'DTSTART:19700329T020000',
            'RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU',
            'END:DAYLIGHT',
            'BEGIN:STANDARD',
            'TZOFFSETFROM:+0200',
            'TZOFFSETTO:+0100',
            'TZNAME:CET',
            'DTSTART:19701025T030000',
            'RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU',
            'END:STANDARD',
            'END:VTIMEZONE',
            'END:VCALENDAR'
        ]);
    }
    
    private function generateEventBlock($event): string
    {
        $eventStartUTS = $event->getEventStartUTS();
		$eventEndUTS = $event->getEventEndUTS();
        $isAllDay = $event->allDay;

        $eventData = [
			'BEGIN:VEVENT',
			'UID:' . $this->generateUID($event),
			'DTSTAMP:' . $this->formatDateTime(new DateTimeImmutable()),
			'DTSTART;' . $this->formatDateTime($eventStartUTS, 'start', $isAllDay),
		];
		
		
		// Check if the event has an end
		if ($eventEndUTS > 0) {
			$eventData[] = 'DTEND;' . $this->formatDateTime($eventEndUTS, 'end', $isAllDay);
		} else {
			// If there is no end and it's not allDay, instead of using the 0 (which is kind of bad because this represents 1970-01-01)
			// we set the end to the end of the day
            // if it's allDay we don't set a time
            if ($isAllDay) {
                $eventData[] = 'DTEND;' . $this->formatDateTime($eventStartUTS, 'end', true);
            } else {
                $endOfDay = (new DateTimeImmutable())->setTimestamp($eventStartUTS)->setTimezone(new DateTimeZone('Europe/Berlin'))->setTime(23, 59, 0)->getTimestamp();
			    $eventData[] = 'DTEND;' . $this->formatDateTime($endOfDay, 'end', false);
            }
		}
		
		$eventData[] = 'SUMMARY:' . $this->escapeString($event->name);

        if ( ! empty($event->location)) {
            $eventData[] = 'LOCATION:' . $this->escapeString($event->location);
        }

        if ( ! empty($event->link)) {
            $eventData[] = 'URL:' . $this->escapeString(getRemoteAddr() . '/event.php?e=' . $event->link);
        }

        $eventData[] = 'END:VEVENT';

		// To debug the Calendar, uncomment the following lines
		// echo '<pre>';
		// var_dump($eventData);
		// echo '</pre>';

        return implode(self::LINE_ENDING, $eventData);
    }
    
    private function generateUID($event): string
    {
        return sprintf(
            '%s-%s@%s',
            uniqid('event-', true),
            hash('crc32', $event->name), /* TODO: rework */
            $_SERVER['HTTP_HOST'] ?? 'fsi.uni-tuebingen.de'
        );
    }
    
    private function formatDateTime($input, string $type = 'start', bool $isAllDay = false): string
    {   
        $timezone = new DateTimeZone('Europe/Berlin');

        if ($input instanceof DateTimeInterface) {
            return $input->setTimezone($timezone)->format('Ymd\THis');
        }

        $timestamp = (int)$input;
        $dt = (new DateTimeImmutable())->setTimestamp($timestamp)->setTimezone($timezone);

        if ($isAllDay) {
            if ($type === 'start') {
                return 'VALUE=DATE:' . $dt->format('Ymd');
            }
        
            return 'VALUE=DATE:' . $dt->modify('+1 day')->format('Ymd');
        }

        return 'TZID=Europe/Berlin:' . $dt->format('Ymd\THis');
    }
    
    private function escapeString(string $text): string
    {
        $text = str_replace(['\\', ',', ';'], ['\\\\', '\,', '\;'], $text);
        return preg_replace('/\R/', '\n', $text);
    }
    
    public function sendICSFile(): void
    {
        if(count($this->events) > 1) {
            $filename = "eei.ics";
        } else {
            $filename = $this->sanitizeFilename($this->events[0]->name) . '.ics';
        }
        
	// To debug the Calendar, comment all the "header" lines
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: ' . self::MIME_TYPE);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');
        
        echo $this->generateICS();
        exit;
    }
    
    private function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9-_.]/', '_', $filename);
    }
}


/* begin procedural code execution */
if (isset($_GET['e'], $_GET['ics'])) {
    /* generate ICS for specific event */
    $eventId = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_ENCODED);
    if ( ! isset($events[$eventId])) {
        header('Location: /', true, 302);
        exit;
    }
    downloadIcsFile($eventId);
    exit;
} elseif(isset($_GET["allevents"])) {
    /* generate ICS for all events (usable live feed) */
    $generator = new ICSGenerator($events);
    $generator->sendICSFile();
    exit;
}

function downloadIcsFile(string $eventId): void
{
    global $events;
    $event = $events[$eventId];
    if (!$event instanceof Event) {
        return;
    }

    $generator = new ICSGenerator(array($event));
    $generator->sendICSFile();
    exit;
}