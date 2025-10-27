<?php
/**
 * Event type
 *
 * This file contains the Event class which represents an event.
 * It also contains the events array which contains all events.
 *
 * To use this file, include it in your PHP file:
 * require_once "event_type.php";
 * global $events;
 */

// Import config because it needs $fp
require_once "config.php";
require_once "i18n/i18n.php";

// Load the Spyc library
require __DIR__ . '/lib/spyc/Spyc.php';

// Metas contains email addresses of people who should be notified when someone registers for an event
if (file_exists(__DIR__ . '/metas.php')) {
	include_once __DIR__ . '/metas.php';
	// import the variable mail_handles from metas.php if existant
	global $mail_handles;
}

global $fp;

/**
 * Represents a FSI Event.
 */
class Event
{
	public string $link;
	public string $name;
	public bool $registrationEnabled;
	public string $location;
	public array $locationMaps;
	public bool $opentoall;
	private int $maxParticipants;
	public bool $dinosAllowed;
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
		$this->name = Event::i18n($data, 'name') ?? $i18n->translate(strtolower($this->link) . '_name') ?? '';

		/* is registration enabled for this event? */
		$this->registrationEnabled = $data['registration_enabled'] ?? TRUE;

		$this->location = $data['location'];
		$this->locationMaps = $data['location_maps'] ?? [];
		$this->opentoall = $data['opentoall'];
		$this->maxParticipants = $data['max_participants'] ?? FALSE;
		$this->dinosAllowed = $data['dinos'] ?? FALSE;
		$this->cancelled = $data['cancelled'] ?? FALSE;
		// Text and info
		$this->text = Event::i18n($data, 'text') ?? $i18n->translate(strtolower($this->link) . '_text') ?? '';
		$this->info = Event::i18n($data, 'info') ?? $i18n->translate(strtolower($this->link) . '_info') ?? '';
		// Date and time of the event
		$this->eventDate = [
			'onTime' => TRUE
		];
		$this->eventDate = array_merge($this->eventDate, $data['event_date'] ?? []);
		$this->registrationDate = $data['registration_date'] ?? [];
		// Form fields
		$this->form = [
			'course_required' => TRUE,
			'food' => FALSE,
			'breakfast' => FALSE,
			'gender' => FALSE,
			'no_alcohol' => FALSE,
		];
		$this->form = array_merge($this->form, $data['form'] ?? []);
		// Misc
		if($this->registrationEnabled) {
			$this->csvPath = $data['csv_path'];
		}
		$this->metas = $data['metas'] ?? [];
		$this->icon = $data['icon'];
	}

	/**
	 * Translate the given key if it is an i18n key.
	 *
	 * @param array $data - The data array.
	 * @param string $key - The key to translate.
	 *
	 * @return string|null - The translated string or null if the key does not exist.
	 */
	private static function i18n(array $data, string $key)
	{
		global $i18n;

		// Check if the key exists in the data array
		if (!isset($data[$key])) {
			return NULL;
		}
		$value = $data[$key];
		if ($i18n::isI18nKey($value)) {
			return $i18n->translate($value);
		}
		return $value;
	}

	/**
	 * Load events from a YAML file.
	 *
	 * @param string $filepath - The path to the YAML file.
	 *
	 * @return array - An array of Event objects.
	 */
	public static function fromYaml(string $filepath): array
	{
		global $mail_handles, $fp, $CONFIG_TERM_SHORT;

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
		$dateFormat = 'd.m.Y H:i';
		foreach ($events as /* @var Event $event */ $event) {
			///// Date parsing /////

			// Event
			// 1. Parse the start and end date of the event
			// 2. Convert the date to a Unix timestamp
			//    If the date is invalid, set the timestamp to 0
			$startDate = DateTime::createFromFormat($dateFormat, $event->eventDate['start']);
			$endDate = DateTime::createFromFormat($dateFormat, $event->eventDate['end'] ?? '');
			$event->eventDate['startUTS'] = $startDate ? $startDate->getTimestamp() : 0;
			$event->eventDate['endUTS'] = $endDate ? $endDate->getTimestamp() : 0;

			// Registration
			$startRegDate = DateTime::createFromFormat($dateFormat, $event->registrationDate['start'] ?? '');
			$endRegDate = DateTime::createFromFormat($dateFormat, $event->registrationDate['end'] ?? '');
			$event->registrationDate['startUTS'] = $startRegDate ? $startRegDate->getTimestamp() : 0;
			$event->registrationDate['endUTS'] = $endRegDate ? $endRegDate->getTimestamp() : 0;

			///// Metas /////
			$metas = $event->metas;
			$event->metas = [];
			foreach ($metas as $value) {
				// Check if the value exists in $mail_handles and append it to the list in $event->metas.
				if (isset($mail_handles[$value])) {
					$event->metas[] = $mail_handles[$value];
				}
			}

			///// CSV path /////
			if($event->registrationEnabled) {
				$event->csvPath = $fp . $CONFIG_TERM_SHORT . '-' . $event->csvPath;
			}
		}

		return $events;
	}

	/**
	 * Check if the user can register for the event.
	 * This is the case if the event is not cancelled, the registration is open and there are spots left.
	 *
	 * @return bool
	 */
	public function canRegister(): bool
	{
		$now = time();
		return (
				($now >= $this->getRegistrationStartUTS() && $this->getRegistrationStartUTS() > 0)
				|| $this->getRegistrationStartUTS() == 0
			)
			&& $now <= $this->getRegistrationEndUTS()
			&& $this->getRemainingSpots() > 0
			&& !$this->cancelled
			&& $this->isUpcoming();
	}

	/**
	 * Check if the event is past.
	 * An event is past if the current time is after the event end time.
	 *
	 * @return bool
	 */
	public function isPast(): bool
	{
		$now = time();
		return !$this->isTakingPlace(
				array(
					'startUTS' => $this->getEventStartUTS(),
					'endUTS' => $this->getEventEndUTS())) &&
			(($this->getEventEndUTS() !== 0 && $now > $this->getEventEndUTS()) ||
				($this->getEventEndUTS() === 0 && $this->getEventStartUTS() < $now));
	}

	/**
	 * Check if the event is upcoming.
	 * An event is upcoming if the current time is before the event start time.
	 *
	 * @return bool
	 */
	public function isUpcoming(): bool
	{
		return !$this->isPast();
	}

	/**
	 * Check if the registration for this event should be enabled or 
	 * if the event entry is just for informational causes.
	 * @return bool
	 */
	public function isRegistrationEnabled(): bool {
		return $this->registrationEnabled;
	}

	/**
	 * Check if the event is open to all.
	 * An event is open to all if registration is optional.
	 * 
	 * @return bool
	 */
	public function isOpentoall(): bool {
		return $this->opentoall;
	}

	/**
	 * Check if the event is active.
	 * An event is active if the current time is between the event start and end time.
	 *
	 * @param bool $exact - If true, the event is only active if the current time is exactly between the start
	 *                        and end time. If false, the event is active if it's the day of the event.
	 *
	 * @return bool
	 */
	private static function isTakingPlace(array $date, bool $exact = FALSE): bool
	{
		$startUTS = $date['startUTS'];
		$endUTS = $date['endUTS'] ?? 0;

		$now = time();
		$start_time = $exact ? $startUTS : strtotime('today midnight', $startUTS);
		$end_time = $endUTS !== 0 ? $endUTS : strtotime('tomorrow midnight', $startUTS);

		return $now >= $start_time && time() <= $end_time;
	}

	/**
	 * Check if the event is taking place.
	 *
	 * @param bool $exact - If true, the event is only taking place if the current time is exactly between the start
	 *                        and end time. If false, the event is taking place if it's the day of the event.
	 *
	 * @return bool
	 */
	public function eventIsTakingPlace(bool $exact = FALSE): bool
	{
		return Event::isTakingPlace(array(
			'startUTS' => $this->getEventStartUTS(),
			'endUTS' => $this->getEventEndUTS()
		), $exact);
	}

	/**
	 * Get the start date of the registration.
	 *
	 * @return int
	 */
	public function getRegistrationStartUTS(): int
	{
		return $this->registrationDate['startUTS'];
	}

	/**
	 * Get the end date of the registration.
	 *
	 * @return int
	 */
	public function getRegistrationEndUTS(): int
	{
		return $this->registrationDate['endUTS'];
	}

	/**
	 * Get the start date of the event.
	 *
	 * @return int
	 */
	public function getEventStartUTS(): int
	{
		return $this->eventDate['startUTS'] ?? 0;
	}

	/**
	 * Get the end date of the event.
	 *
	 * @return int
	 */
	public function getEventEndUTS(): int
	{
		return $this->eventDate['endUTS'] ?? 0;
	}

	/**
	 * Get the event date as a string.
	 *
	 * @param array $options - array of options
	 *                        <ul>
	 *                        <li>compact: show date in compact mode</li>
	 *                        </ul>
	 *
	 * @return string
	 */
	public function getEventDateString(array $options = array()): string
	{
		return $this->dateTimeToString(array(
			$this->getEventStartUTS(),
			$this->getEventEndUTS(),
			$this->eventDate['onTime'] ?? TRUE
		), $options);
	}

	public function isMultiDayEvent() {
		if( $this->getEventEndUTS() == null) return false;
		$compareFormat = "d.m.Y";
		return date($compareFormat, $this->getEventStartUTS()) 
			!= date($compareFormat, $this->getEventEndUTS());
	}

	/**
	 * Get the registration date as a string.
	 *
	 * @return string
	 */
	public function getRegistrationDateString(): string
	{
		return $this->dateTimeToString(array(
			$this->getRegistrationStartUTS(),
			$this->getRegistrationEndUTS(),
			TRUE
		), array('compact' => FALSE, 'noEnd' => TRUE));
	}

	public function getRegistrationEndDateString(): string
	{
		return $this->dateTimeToString(array(
			$this->getRegistrationEndUTS(),
			null,
			TRUE
		), array('compact' => FALSE, 'noEnd' => TRUE));
	}

	/**
	 * Build a date and time string for the given start and end date.
	 *
	 * @param array $date
	 * @param array $options - array of options
	 *                        <ul>
	 *                        <li>compact: show date in compact mode</li>
	 *                        </ul>
	 *
	 * @return string
	 */
	public static function dateTimeToString(array $date, array $options = array()): string
	{
		global $i18n;

		$isGerman = $i18n->getLanguage() == 'de';
		$dateFormat = $isGerman ? 'd.m.y' : 'y-m-d';
		$timeFormat = $isGerman ? 'H:i' : 'h:i A';


		$startDate = date($dateFormat, $date[0] ?? 0);
		$startTime = date($timeFormat, $date[0] ?? 0);
		$endDate = date($dateFormat, $date[1] ?? 0);
		$hasEndDate = $date[1] !== 0;
		$onTime = $date[2] ?? TRUE;

		// Parse options
		$compact = isset($options['compact']) && $options['compact'];
		$noEnd = isset($options['noEnd']) && $options['noEnd'];
		$noTime = isset($options['no_time']) && $options['no_time'];
		// Check if the start and end date are on different days
		$isStartEndDiffDays = $startDate != $endDate && $hasEndDate;

		$time = time();
		$today = date($dateFormat, $time);
		if ($today === $startDate) {
			$startDate = $i18n['time_today'];
		}

		// Check if the event is taking place
		$isTakingPlace = Event::isTakingPlace(array('startUTS' => $date[0], 'endUTS' => $date[1]));

		$dateAndTime = $startDate;
		if ($compact) {
			// compact mode
			// 1.1.2017
			// 1.1.2017 - 2.1.2017
			// 1.1.2017 12:00 Uhr

			if (Event::isTakingPlace(array('startUTS' => $date[0], 'endUTS' => $date[1]), TRUE)) {
				$dateAndTime = $i18n['time_takingPlace'];
			} elseif ($isTakingPlace) {
				$dateAndTime = $i18n['time_today'];
			} elseif (!$isStartEndDiffDays && !$noTime) {
				$dateAndTime = $dateAndTime . '<br>' . $startTime;
			}
		} else {
			// full date and time
			// 1.1.2017 um 12:00 Uhr
			// 1.1.2017 ab 12:00 Uhr
			// 1.1.2017 um 12:00 Uhr - 2.1.2017

			if (!$noTime) {
				$startTimeKey = $onTime ? 'time_at' : 'time_from';
				$dateAndTime = "$dateAndTime {$i18n->translate($startTimeKey, array('TIME' => $startTime))}";
			}
		}

		if ($isStartEndDiffDays && !$noEnd && $hasEndDate && !$isTakingPlace) {
			$dateAndTime = $i18n->translate('date_from') . " $dateAndTime<br/>" . $i18n->translate('date_to') . " $endDate";
		}

		return $dateAndTime;
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

}

// Load the events from the events.yaml file
// The events must be imported using the following code:
// global $events;
$events = Event::fromYaml(__DIR__ . '/events.yml');
