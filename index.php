<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n, $CONFIG_TERM, $FILE_REVISION, $events;

// Load environment variables from the .env file
loadEnv('.env');

// Ensure the eei-registration folder exists
createEeiRegistrationFolder();

// Define a function to sort events by their start date
/**
 * Sorts events chronologically by their start time
 * @param Event $a
 * @param Event $b
 * @return int
 */
function sortByDate(Event $a, Event $b): int
{
    return $a->getEventStartUTS() - $b->getEventStartUTS();
}

usort($events, 'sortByDate');
?>

<!DOCTYPE html>
<html lang="<?= $i18n->getLanguage() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION ?>">
    <link rel="stylesheet" href="css/icons.css<?= $FILE_REVISION ?>">
    <link rel="stylesheet" href="css/i18n.css<?= $FILE_REVISION ?>">
    <title><?= $i18n['title'] ?></title>
</head>

<body>
    <!-- Icons courtesy of fontawesome.com under CC BY 4.0 License -->
    <!-- BBQ-Grill icon by Smashicons from flaticon.com -->
    <div id="center">
        <h1><?= $i18n['title'] ?> - <?= $CONFIG_TERM ?></h1>

        <div class="container">
            <label>
                <div class="language-switcher">
                    <select id="lang-selection" aria-label="Select language">
                        <option value="de" <?= $i18n->getLanguage() === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                        <option value="en" <?= $i18n->getLanguage() === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                    </select>
                </div>
            </label>
        </div>

        <div class="container">
            <?php
            // Sort events by activity status and date
            $sorted_events = $events;
            usort($sorted_events, function (Event $a, Event $b) {
                if ($a->isPast() && !$b->isPast()) {
                    return 1; // Past events appear after active ones
                } elseif (!$a->isPast() && $b->isPast()) {
                    return -1;
                }
                return $a->getEventStartUTS() - $b->getEventStartUTS();
            });

            // Render the sorted events
            foreach ($sorted_events as $event) { ?>
                <a href="event.php?e=<?= $event->link ?>&lang=<?= $i18n->getLanguage() ?>">
                    <div class="box icon <?= $event->icon ?> <?= $event->isPast() ? 'past' : '' ?> float-style <?= $event->eventIsTakingPlace() ? 'today' : '' ?>">
                        <p class="name"><?= $event->name ?></p>
                        <p class="date"><?= $event->getEventDateString(['compact' => true]) ?></p>
                    </div>
                </a>
            <?php } ?>
        </div>

        <br>
        <div class="footnotes">
            <p><?= $i18n['index_savedDataDisclaimer'] ?></p>
            <br>
            <div class="container">
                <input id="btn-clr" type="submit" value="<?= $i18n['delete'] ?>"
                       onclick="!localStorage.clear() && alert('<?= $i18n['index_deletedData'] ?>')">
            </div>
            <div class="container">
                <a href="https://github.com/fsi-tue/eei">
                    <div class="link color-border">
                        Source Code auf GitHub
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Language change handler
        document.getElementById('lang-selection').addEventListener('change', function () {
            location.href = `index.php?lang=${this.value}`;
        });
    </script>
</body>

</html>
