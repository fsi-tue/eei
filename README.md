# eei - Ersti Einführungs Interface

## Setup
* Requires `php` to be installed
* Requires `composer` to be installed
* Run `composer install` to install dependencies
* Create a `.env` file in the root directory and add the following variables:

```dotenv
# Example Configuration
SENDER_EMAIL=foobaz@uni-tuebingen.de
SENDER_NAME=foo
SENDER_PASSWORD=bar
EMAIL_HOST=smtpserv.uni-tuebingen.de
EMAIL_PORT=587
```

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
         #   Time of event (hour, minute, second, MONTH, DAY, year)
         #   This timestamp is used by prune_events.php, so make sure it is set correctly, otherwise the registrations will be deleted automatically!
            "startUTS" => mktime('18', '0', '0', '04', '20', '2023'),
         #   Date of event (hour, minute, second, MONTH, DAY, year)
         #   Time is ignored, only date is used 
            "endUTS" => mktime('23', '0', '0', '04', '20', '2023'),
         # Has the event a strict time frame?
            "onTime" => FALSE,
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
            "max_participants" => 260 ,
         # time frame for registration:
            "start_of_registration" => mktime('20', '0', '0', '09', '23', '2021'),
            # set start_of_registration to FALSE to open registration for immediately
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021'),
         # Text (html) description of event
            "text" => "Der Ersti Spieleabend<br>
            LOREM IPSUM YOUR DESCRIPTION",
         # Info Box to show.
            "info" => "Für die Veranstaltung gilt 3G."
            # Empty string to hide infobox
        ],```
