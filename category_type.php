<?php
/**
 * Category type
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
require_once __DIR__ . '/lib/spyc/Spyc.php';


global $fp;

/**
 * Represents a Category.
 */
class Category
{
	public string $link;
	public string $name;
	public string $color_fg;
	public string $color_bg;
	public bool $ribbon;

	public function __construct(array $data)
	{
		global $i18n;
		$this->link = $data['link'];
		$this->name = Category::i18n($data, 'name') ?? $i18n->translate(strtolower($this->link) . '_name') ?? '';
		$this->color_fg = $data["color_fg"];
		$this->color_bg = $data["color_bg"];
		$this->ribbon = $data["ribbon"];
	}

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

	public static function fromYaml(string $filepath): array
	{
		// Load all events from the events.yaml file and initialize the $events array
		$events = spyc_load_file($filepath);
		$categories = $events['categories'] ?? [];
		// Get keys of the events array
		$keys = array_keys($categories);
		// Create an array of Event objects where the link is the key of the event
		$category_map = [];
		for ($i = 0; $i < count($categories); $i++) {
			$category = $categories[$keys[$i]];
			$category['link'] = $keys[$i];
			$category_map[$keys[$i]] = new Category($category);
		}
		$categories = $category_map;

		return $categories;
	}

}

// Load the categories from the events.yaml file
// The categories must be imported using the following code:
// global $events;
$categories = Category::fromYaml(__DIR__ . '/events.yml');