# eei - Erst Einführungs Interface

## Testing
* Requires `php` to be installed
* Run a local test instance using `php -S localhost:8000`

## Adding/Updating an event:
### event_data.php
To add an event copy the dummy event and modify the values. Then, append this event to the $events array
The oder in this array defines the shown order

Example:
```php
        "SP1" => [
         # Link MUST be the same as the key (top left)
            "link" => 'SP1',
            "name" => 'Ersti-Spieleabend',
         # Is the event active? If not, this event won't be shown.
            "active" => TRUE,
            "cancelled" => FALSE,
         # Studiengang
            "course_required" => TRUE,
         # Food preferences
            "food" => FALSE,
         # Breakfast preferences
            "breakfast" => FALSE,
         # Path to safe file: {fp}nameOfEvent.csv
            "path" => "{$fp}spieleabend.csv",
         # Icon to show: beer, cap, clock, cocktail, dice, film, food, grill, hiking, home, marker, route, sings
            "icon" => 'dice',
            "location" => 'Terrasse Sand',
            "date" => '25.09.21 ab 18:00',
         #   Time of event (hour, minute, second, MONTH, DAY, year)
            "uts" => mktime('18', '0', '0', '09', '25', '2022'),
            "max_participants" => 260 ,
         # Override the default registration deadline? (74h prior the event)
            "registration_override" => TRUE,
         # If registration_override=TRUE, set the deadline to:
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021'),
         # Text (html) description of event
            "text" => "Der Ersti Spieleabend<br>
            LOREM IPSUM YOUR DESCRIPTION",
         # Info Box to show.
            "info" => "Für die Veranstaltung gilt 3G."
            # Empty string to hide infobox
        ],```
