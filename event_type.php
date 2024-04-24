<?php
// Import config because it needs $fp
require_once "config.php";

// Load the Spyc library
require __DIR__ . '/lib/spyc/Spyc.php';

// Metas contains email addresses of people who should be notified when someone registers for an event
if (file_exists(__DIR__ . 'metas.php')) {
	include_once __DIR__ . 'metas.php';
	// import the variable mail_handles from metas.php if existant
	global $mail_handles;
}

global $fp;

// Event class
class Event
{
	public string $link;
	public string $name;
	public string $location;
	public int $maxParticipants;
	public bool $cancelled;
	public string $text;
	public string $info;
	private array $eventDate;
	private array $registrationDate;
	public array $form;
	public string $csvPath;
	public string $icon;
	public array $metas;

	public function __construct(array $data)
	{
		global $i18n;

		$this->link = $data['link'];
		// If the name is set, use it, otherwise use the translation
		$this->name = $data['name'] ?? $i18n->translate($this->link . '_name') ?? '';
		if ($this->name === '') {
			throw new Exception('Name for event ' . $this->link . ' is empty');
		}
		$this->location = $data['location'];
		$this->maxParticipants = $data['max_participants'];
		$this->cancelled = $data['cancelled'] ?? false;
		// Text and info
		$this->text = $data['text'] ?? $i18n->translate($this->link . '_text') ?? '';
		$this->info = $data['info'] ?? $i18n->translate($this->link . '_info') ?? '';
		// Date and time of the event
		$this->eventDate = $data['event_date'];
		$this->registrationDate = $data['registration_date'];
		// Form fields
		$this->form = [
			'course_required' => false,
			'food' => false,
			'breakfast' => false,
		];
		$this->form = array_merge($this->form, $data['form'] ?? []);
		// Misc
		$this->csvPath = $data['csv_path'];
		$this->icon = $data['icon'];
		$this->metas = $data['metas'];
	}

	public static function fromYaml(string $filepath): array
	{
		global $mail_handles, $fp;

		// Load all events from the events.yaml file and initialize the $events array
		$events = spyc_load_file($filepath);

		$events = $events['events'] ?? [];
		// Get keys of the events array
		$keys = array_keys($events);

		// Create an array of Event objects where the link is the key of the event
		$event_map = [];
		for ($i = 0; $i < count($events); $i++) {
			$event = $events[$keys[$i]];
			$event['link'] = $keys[$i];
			$event_map[$keys[$i]] = new Event($event);
		}
		$events = $event_map;

		// Parse the date and time of each event
		$dateFormat = 'd.m.y H:i';
		foreach ($events as /* @var Event $event */ $event) {
			// Date parsing function
			// Event
			$startDate = DateTime::createFromFormat($dateFormat, $event->eventDate['start']);
			$endDate = DateTime::createFromFormat($dateFormat, $event->eventDate['end']);
			$event->eventDate['startUTS'] = $startDate->getTimestamp();
			$event->eventDate['endUTS'] = $endDate->getTimestamp();

			// Registration
			$startRegDate = DateTime::createFromFormat($dateFormat, $event->registrationDate['start']);
			$endRegDate = DateTime::createFromFormat($dateFormat, $event->registrationDate['end']);
			if ($startRegDate === false || $endRegDate === false) {
				throw new Exception('Invalid date format');
			}
			$event->registrationDate['startUTS'] = $startRegDate->getTimestamp();
			$event->registrationDate['endUTS'] = $endRegDate->getTimestamp();

			// Map metas to mail handles
			$metas = $event->metas;
			$event->metas = [];
			foreach ($metas as $key => $value) {
				$event->metas[$key] = $mail_handles[$value] ?? '';
			}

			// Csv path
			$event->csvPath = realpath(__DIR__ . '/../eei-registration/') . '/' . $event->csvPath;
		}

		return $events;
	}

	/**
	 * Check if the user can register for the event.
	 * This is the case if the event is not cancelled, the registration is open and there are spots left.
	 * @return bool
	 */
	public function canRegister(): bool
	{
		$now = time();
		return $now >= $this->getRegistrationStart()
			&& $now <= $this->getRegistrationEnd()
			&& $this->getRemainingSpots() > 0
			&& !$this->cancelled
			&& $this->isActive();
	}

	/**
	 * Check if the event is active.
	 * Active means that the event has not started yet and is not over yet
	 * @return bool
	 */
	public function isActive(): bool
	{
		$now = time();
		return $now <= $this->getEndDate();
	}

	public function getRegistrationStart(): int
	{
		return $this->registrationDate['startUTS'];
	}

	public function getRegistrationEnd(): int
	{
		return $this->registrationDate['endUTS'];
	}

	public function getStartDate(): int
	{
		return $this->eventDate['startUTS'];
	}

	public function getEndDate(): int
	{
		return $this->eventDate['endUTS'];
	}

	/**
	 * Build a date and time string for the given start and end date.
	 *
	 * @param array $E - event
	 * @param array $options - array of options
	 *                        <ul>
	 *                        <li>compact: show date in compact mode</li>
	 *                        </ul>
	 *
	 * @return string
	 */
	public function dateTimeToString(array $options = array()): string
	{
		global $i18n;

		$isGerman = $i18n->getLanguage() == 'de';
		$dateFormat = $isGerman ? 'd.m.y' : 'y-m-d';
		$timeFormat = $isGerman ? 'H:i' : 'h:i A';

		$startDate = date($dateFormat, $this->getStartDate() ?? 0);
		$startTime = date($timeFormat, $this->getStartDate() ?? 0);
		$endDate = date($dateFormat, $this->getEndDate() ?? 0);
		$onTime = $this->eventDate['onTime'] ?? true;
		$compact = isset($options['compact']) && $options['compact'];
		// Check if the start and end date are on different days
		$isStartEndDiffDays = $startDate != $endDate;


		$dateAndTime = $startDate;
		if ($compact) {
			// compact mode
			// 1.1.2017
			// 1.1.2017 - 2.1.2017
			// 1.1.2017 12:00 Uhr

			if (!$isStartEndDiffDays) {
				$dateAndTime = $dateAndTime . '<br>' . $startTime;
			}
		} else {
			// full date and time
			// 1.1.2017 um 12:00 Uhr
			// 1.1.2017 ab 12:00 Uhr
			// 1.1.2017 um 12:00 Uhr - 2.1.2017

			if ($isGerman) {
				$dateAndTime = $dateAndTime . ($onTime ? ' um ' : ' ab ') . $startTime . ' Uhr';
			} else {
				$dateAndTime = $dateAndTime . ($onTime ? ' at ' : ' from ') . $startTime;
			}

		}
		if ($isStartEndDiffDays) {
			$dateAndTime = $dateAndTime . ' - ' . $endDate;
		}

		return $dateAndTime;
	}

	public function getInfo(): string
	{
		return $this->info;
	}

	public function getLocation(): string
	{
		return $this->location;
	}

	public function getIcon(): string
	{
		return $this->icon;
	}

	public function getRemainingSpots(): int
	{
		if ($this->maxParticipants) {
			$filepath = $this->csvPath;
			// Check if the file exists
			if (!file_exists($filepath)) {
				return $this->maxParticipants;
			}

			$header_line_count = 1;
			$csv_file = file($filepath, FILE_SKIP_EMPTY_LINES);
			$spots = $this->maxParticipants - (count($csv_file) - $header_line_count);
			if ($spots <= 0) {
				$spots = 0;
			}
			return $spots;
		}
		return 0;
	}

	public function getMeta(string $key)
	{
		return $this->metas[$key] ?? '';
	}
}

$events = Event::fromYaml(__DIR__ . '/events.yml');
