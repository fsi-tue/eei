<?php
require_once('config.php');
require_once('utils.php');
require_once('event_data.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();

global $CONFIG_TERM, $FILE_REVISION, $events;

// Loads the environment variables from the .env file
loadEnv('.env');
function sortByDate($a, $b)
{
    return ($a['startUTS'] > $b['startUTS']);
}

// Sort the data using the custom comparison function
usort($events, 'sortByDate');

?>

<!DOCTYPE html>
<html lang="<?= $localizer->getLang() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/icons.css<?= $FILE_REVISION; ?>">
    <title><?= $localizer['title'] ?></title>
</head>

<body>
    <!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
    <!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
    <div id="center">
        <h1><?= $localizer['title'] ?> - <?= $CONFIG_TERM ?></h1>

        <div class="container">
            <label>
                <div class="select-container">
                    <select id="lang-selection" aria-description="Select language">
                        <option value='de' <?= $localizer->getLang() === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                        <option value="en" <?= $localizer->getLang() === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
                    </select>
                </div>
            </label>
        </div>

        <div class="container">
            <?php
            // Sort events by date and if they are active
            $sorted_events = $events;
            usort($sorted_events, function ($a, $b) {
                // sort by active first, then by date
                $a_status = $a['active'] && time() < $a['startUTS'];
                $b_status = $b['active'] && time() < $b['startUTS'];

                if ($a_status == $b_status) {
                    return $a['startUTS'] > $b['startUTS'];
                } else {
                    return $a_status < $b_status;
                }
            });

            foreach ($sorted_events as $E) {
                if ($E['active']) {
                    // date in first line, time shall go in new line
                    $date = $E['date'];
                    $date = replaceFirstOccurence(" ", ",<br>", $date);
            ?>
                    <a href="event.php?e=<?= $E['link'] ?>&lang=<?= $localizer->getLang() ?>">
                        <div class="box icon <?= $E['icon'] ?> <?= time() > $E['startUTS'] ? ' past ' : '' ?> float-style">
                            <p class="name"><?= $E['name'] ?></p>
                            <p class="date"><?= showDateAndTime($E, array('compact' => TRUE)) ?></p>
                        </div>
                    </a>
            <?php
                }
            }
            ?>
        </div>
        <br>
        <div class="footnotes">
            <p>
                <?php echo $localizer['index_savedDataDisclaimer']; ?>
            </p>
            <br>
            <div class="container">
                <input id="btn-clr" type="submit" value="<?= $localizer['delete'] ?>" onclick="!localStorage.clear() && alert('<?= $localizer['index_deletedData'] ?>')">
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