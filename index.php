<?php
require_once('config.php');
require_once('utils.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();
// import event_data after localizer, because event_data uses $localizer
require_once('event_data.php');

global $CONFIG_TERM, $FILE_REVISION, $events;
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/index.css<?= $FILE_REVISION; ?>">
    <title><?= $localizer['title'] ?></title>
</head>
<body>
<!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
<!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
<div id="center">
    <h1><?= $localizer['title'] ?> - <?= $CONFIG_TERM ?></h1>

    <div class="container">
        <label>
            <select id="lang-selection">
                <option value='de' <?= $localizer->getLang() === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                <option value="en" <?= $localizer->getLang() === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
            </select>
        </label>
    </div>

    <div class="container">
        <?php
        foreach ($events as $e) {
        if ($e['active']) {
        // date in first line, time shall go in new line
        $date = $e['date'];
        $date = replaceFirstOccurence(" ", ",<br>", $date);
        ?>
        <a href='event.php?e=<?= $e['link'] ?>&lang=<?= $localizer->getLang() ?>'>
            <div class='box icon <?= $e['icon'] ?>'>
                <p class='name'><?= $e['name'] ?></p>
                <p class='date'><?= showDateAndTime($e['startUTS'], $e['endUTS'], array('compact' => true)) ?></p>
            </div>
        </a>
        <?php
        }
        }
        ?>
    </div>
    <br>
    <div>
        <?php
        echo $localizer['index_savedDataDisclaimer']; ?><br>
        <input id="btn-clr" type="submit" value="<?= $localizer['delete'] ?>"
               onclick="!localStorage.clear() && alert('Daten erfolgreich aus dem Browser gelöscht.')">
    </div>
    <a style="text-align: center" href="https://github.com/fsi-tue/eei">Source Code</a>
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
