# eei - Ersti Einführungs Interface

## Setup Instructions

1. Download and install php: https://www.php.net/manual/install.php
2. Create a `.env` file in the root directory and add the following variables:

   If you have a shell with `bash` or `zsh` installed, you can use the following command to generate a random password:

    ```bash
    touch .env
    ```

   Then add the following content to the `.env` file:

    ```dotenv
    # Example Configuration
    SENDER_EMAIL=foobaz@uni-tuebingen.de
    SENDER_USERNAME=foobaz
    SENDER_NAME=foo
    SENDER_PASSWORD=bar
    EMAIL_HOST=smtpserv.uni-tuebingen.de
    EMAIL_PORT=587
    ```

## Testing the setup

1. Install `php` as described above
2. Open the terminal/command prompt and navigate to the root directory of this project
3. Run `php -S localhost:8000` in the terminal. This will start a local server on port 8000
4. Open a browser and navigate to: <http://localhost:8000>

## Adding/Updating an event

The events are stored in the file `events.yml`. To add or update an event, you need to edit this file.

### [`events.yml`](events.yml)

To add / edit an event

1. Copy or edit the dummy event and modify the values.
2. Append this event to the $events array.
3. Add or change the german (`i18n/de.json`) and english `i18n/en.json` version inside the `i18n` folder.

Usage of the `events.yml` file:

---

**NOTE:**

- The `SP1` in the example below is a unique identifier for the event. It should be unique and not repeated in the file.
- To avoid copy paste of `name`, `text` and `info` you specify the key in the i18n files
  Example:
   ```yaml
  SP2:
      # This allows to reuse the name from the i18n files
      name: "i18n:sp1_name"
    
      # This allows to reuse the text from the i18n files
      text: "i18n:sp1_text"
  
      # ...
  ```

Full Example:

```yaml
# Dummy event
# Instead of SP1, use a unique identifier for the event, e.g. WD1 for Wanderung 1 or SP2 for Spieleabend 2.
SP1:
  # Name of the event.
  # Not required!
  # IMPORTANT: If not set, it will look up the name in the i18n files.
  name: "Spieleabend"

  # Boolean value indicating if the event was cancelled.
  # Not required!
  # IMPORTANT: If not set, it will be set to false.
  cancelled: false

  # Text of the event
  # Not required!
  # IMPORTANT: If not set, it will look up the description in the i18n files.
  # You can use HTML tags in the text.
  # Example: "Ein <strong>gemütlicher Abend</strong> mit Spielen und Getränken."
  text: "Ein gemütlicher Abend mit Spielen und Getränken."

  # Additional information about the event.
  # Not required!
  # IMPORTANT: If not set, it will look up the info in the i18n files.
  info: "Für die Veranstaltung gilt 3G."

  # Location where the event will take place. Can be a specific address or a general area description.
  location: "Sand 14, A301"

  # Maximum number of participants that can attend the event.
  max_participants: 120

  # Event timing details.
  event_date:
    # Start date and time of the event, formatted as DD.MM.YYYY HH:MM.
    start: "12.04.2024 20:00"

    # End date and time of the event, formatted as DD.MM.YYYY HH:MM.
    # Not required
    end: "12.04.2024 23:00"

    # Boolean value indicating if the event is expected to start exactly on time.
    onTime: true

  # Registration period for the event.
  registration_date:
    # Start date and time for registration, formatted as DD.MM.YYYY HH:MM.
    # Not required
    start: "27.03.2024 00:00"
    # End date and time for registration, ensuring it ends before the event starts.
    end: "12.04.2024 19:00"

  # Additional information required for the event registration
  # Not required! Below are the default values.
  form:
    # Ask for breakfast preferences
    # By default, this is set to false
    breakfast: false
    # Ask for food preferences
    # By default, this is set to false
    food: false
    # Ask for course information
    # By default, this is set to true
    course_required: true

    # Path to the CSV file associated with the event. Include the file extension.
    csv_path: "ersti-kneipentour1.csv"

    # Icon representing the event, used in UI elements. Should match a file name or identifier.
    # Available icons: beer, cap, clock, cocktail, dice, film, food, grill, hiking, home, marker, route, sings
    icon: "route"

    # Metas for the event
    metas:
      # Email addresses to send registration to
      # The mail_handles has to be modified in metas.php
      # More information about this found in chapter "metas.php" down below
      - "michi"
      - "josef"
```

### `metas.php`

Since the email address is not supposed to be public, it is stored in a separate file
that is not tracked by git.

To add / edit an email address, add / edit the following line in `metas.php`:

```php
<?php
# add mails by adding "handle" => "...@student.uni-tuebingen.de" at the bottom

$mail_handles = [
	"handle"	          => "valid.adress@student.uni-tuebingen.de",
	"another_handle"	 => "valid.adress47@student.uni-tuebingen.de",
];
```

The variable `$mail_handles` is imported as global in `event_type.php` if the file `metas.php` exists.
