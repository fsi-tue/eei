<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

class ICSGenerator
{
    private const MIME_TYPE = 'text/calendar';
    private const LINE_ENDING = "\r\n";
    
    private Event $event;
    
    public function __construct(Event $event)
    {
        $this->event = $event;
    }
    
    public function generateICS(): string
    {
        return implode(self::LINE_ENDING, [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//FSI//Calendar//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            $this->generateEventBlock(),
            'END:VCALENDAR'
        ]);
    }
    
    private function generateEventBlock(): string
    {
        $eventData = [
			'BEGIN:VEVENT',
			'UID:' . $this->generateUID(),
			'DTSTAMP:' . $this->formatDateTime(new DateTimeImmutable()),
			'DTSTART:' . $this->formatDateTime((new DateTimeImmutable())->setTimestamp($this->event->getEventStartUTS())),
		];
		
		$eventStartUTS = $this->event->getEventStartUTS();
		$eventEndUTS = $this->event->getEventEndUTS();
		
		// Check if the event has an end
		if ($eventEndUTS > 0) {
			$eventData[] = 'DTEND:' . $this->formatDateTime((new DateTimeImmutable())->setTimestamp($eventEndUTS));
		} else {
			// If there is no end, instead of using the 0 (which is kind of bad because this represents 1970-01-01)
			// we set the end to the end of the day
			$endOfDay = (new DateTimeImmutable())->setTimestamp($eventStartUTS)->setTime(23, 59, 59);
			$eventData[] = 'DTEND:' . $this->formatDateTime($endOfDay);
		}
		
		$eventData[] = 'SUMMARY:' . $this->escapeString($this->event->name);

        if (!empty($this->event->description)) {
            $eventData[] = 'DESCRIPTION:' . $this->escapeString($this->event->text);
        }

        if (!empty($this->event->location)) {
            $eventData[] = 'LOCATION:' . $this->escapeString($this->event->location);
        }

        if (!empty($this->event->link)) {
            $eventData[] = 'URL:' . $this->escapeString(getRemoteAddr() . '/event.php?e=' . $this->event->link);
        }

        $eventData[] = 'END:VEVENT';

		// To debug the Calendar, uncomment the following lines
		// echo '<pre>';
		// var_dump($eventData);
		// echo '</pre>';

        return implode(self::LINE_ENDING, $eventData);
    }
    
    private function generateUID(): string
    {
        return sprintf(
            '%s-%s@%s',
            uniqid('event-', true),
            hash('crc32', $this->event->name),
            $_SERVER['HTTP_HOST'] ?? 'fsi.uni-tuebingen.de'
        );
    }
    
    private function formatDateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->format('Ymd\THis\Z');
    }
    
    private function escapeString(string $text): string
    {
        $text = str_replace(['\\', ',', ';'], ['\\\\', '\,', '\;'], $text);
        return preg_replace('/\R/', '\n', $text);
    }
    
    public function sendICSFile(): void
    {
        $filename = $this->sanitizeFilename($this->event->name) . '.ics';
        
		// To debug the Calendar, comment all the "header" lines
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


if (isset($_GET['e'], $_GET['ics'])) {
    $eventId = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_ENCODED);
    
    if (!isset($events[$eventId])) {
        header('Location: /', true, 302);
        exit;
    }

    downloadIcsFile($eventId);
}

function downloadIcsFile(string $eventId): void
{
    global $events;
    $event = $events[$eventId];
    if (!$event instanceof Event) {
        return;
    }

    $generator = new ICSGenerator($event);
    $generator->sendICSFile();
    exit;
}