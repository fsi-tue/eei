# eei - Ersti Einführungs Interface

## Setup

* Requires `php` to be installed
* Create a `.env` file in the root directory and add the following variables:

```dotenv
# Example Configuration
SENDER_EMAIL=foobaz@uni-tuebingen.de
SENDER_USERNAME=foobaz
SENDER_NAME=foo
SENDER_PASSWORD=bar
EMAIL_HOST=smtpserv.uni-tuebingen.de
EMAIL_PORT=587
```

## Testing

* Requires `php` to be installed
* Run a local test instance using `php -S localhost:8000`

## Adding/Updating an event:

### [`event_data.php`](event_data.php)

To add / edit an event
1. copy / edit the dummy event and modify the values.
2. append this event to the $events array.
3. add / change the german / english version inside `localisation/en.json` / `localisation/de.json`

Example:

```php
        "SP1" => [
         # Link MUST be the same as the key (top left)
            "link" => 'SP1',
            "name" => $localizer['sp1_name'],
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
            "text" => $localizer['sp1_text'],
         # Metas for the event
            "metas" => [
                # Email address to send registration to
                # This has to be modified in metas.php
                $EXAMPLE_META
            ],
         # Info Box to show.
            "info" => "Für die Veranstaltung gilt 3G."
            # Empty string to hide infobox
        ],
```

### `metas.php`

Since the email address is not supposed to be public, it is stored in a separate file
that is not tracked by git. To add / edit an email address, add / edit the following
line in `metas.php`:

```php
<?php
$EXAMPLE_META = "engaged-fsi@student.uni-tuebingen.de";
...
```

---
IMPORTANT:

DO NOT COMMIT CHANGES IN `metas.php` TO GIT!

---

It must be a valid UT email address.