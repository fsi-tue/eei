<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n, $CONFIG_TERM, $FILE_REVISION, $events;

// Loads the environment variables from the .env file
loadEnv('.env');

// Create eei-registration folder if it does not exist
createEeiRegistrationFolder();

// Sort the data using the custom comparison function
/**
 * Sorts the events by date
 * @param Event $a
 * @param Event $b
 * @return bool
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
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/icons.css<?= $FILE_REVISION; ?>">
    <title><?= $i18n['title'] ?></title>
</head>

<body>
<!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
<!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
<div id="center">
    <h1><?= $i18n['title'] ?> - <?= $CONFIG_TERM ?></h1>

    <div class="container">
        <label>
            <div class="select-container">
                <select id="lang-selection" aria-description="Select language">
                    <option value='de' <?= $i18n->getLanguage() === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                    <option value="en" <?= $i18n->getLanguage() === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                </select>
            </div>
        </label>
    </div>

    <div class="container">
		<?php
		// Sort events by date and if they are active
		$sorted_events = $events;
		usort($sorted_events, function (Event $a, Event $b) {
			// 1. upcoming events first
            // 2. then sort by date
            if ($a->isUpcoming() && !$b->isUpcoming()) {
                return -1;
            } elseif (!$a->isUpcoming() && $b->isUpcoming()) {
                return 1;
            } else {
                return $a->getEventStartUTS() - $b->getEventStartUTS();
            }
		});

		// Display the events
		foreach ($sorted_events as /* @var Event $event */
				 $event) {
            ?>
            <a href="event.php?e=<?= $event->link ?>&lang=<?= $i18n->getLanguage() ?>">
                <div class="box icon <?= $event->icon ?> <?= $event->isPast() ? ' past ' : '' ?> float-style <?= $event->isActive() ? 'today' : '' ?>">
                    <p class="name"><?= $event->name ?></p>
                    <p class="date"><?= $event->getEventDateString(array('compact' => true)) ?></p>
                </div>
            </a>
			<?php
		}
		?>
    </div>
    <br>
    <div class="footnotes">
        <p>
			<?php echo $i18n['index_savedDataDisclaimer']; ?>
        </p>
        <br>
        <div class="container">
            <input id="btn-clr" type="submit" value="<?= $i18n['delete'] ?>"
                   onclick="!localStorage.clear() && alert('<?= $i18n['index_deletedData'] ?>">
        </div>
        <div class="container">
            <a href="https://github.com/fsi-tue/eei">
                <div class="link color-border">
                    Source Code auf Github
                </div>
            </a>
        </div>
    </div>

</div>

<script>
	// change language
	const langSelection = document.getElementById('lang-selection')
	langSelection.addEventListener('change', () => {
		this.location.href = `index.php?lang=${langSelection.value}`
	})
</script>
</body>

</html>
