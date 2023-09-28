<?php
require_once('config.php');
require_once('utils.php');
require_once('localisation/localizer.php');
$localizer = new Localizer();
// import event_data after localizer, because event_data uses $localizer
require_once('event_data.php');

// global variables
global $events, $CONFIG_TERM;

# If event id is not set, redirect to main page.
if (!isset($_GET["e"])) {
    header("Location:/");
    die();
}

$event_id = filter_input(INPUT_GET, "e", FILTER_SANITIZE_ENCODED);

# If event id is unknown, redirect to main page.
if (!array_key_exists($event_id, $events)) {
    header("Location:/");
    die();
}

$E = $events[$event_id];
?>
<!DOCTYPE html>
<html lang="<?= $localizer->getLang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title> <?= "{$E['name']} - $CONFIG_TERM"; ?></title>
</head>
<body>
<div id="center">
    <div class="block">
        <h1><?= "{$E['name']} - $CONFIG_TERM"; ?></h1>
        <h2 class="description icon clock"><?= showDateAndTime($E) ?></h2>
        <h2 class="description icon marker"><?= $E['location']; ?></h2>
        <h2 class="description"><?= $localizer['remaining'] . ": " . getNumberOfRemainingSpots($E) ?></h2>
        <?= $E["text"] ?>
        <br>
        <?php
        if ($E['info'] != '') {
            ?>
            <div class='block info'><?= $E['info'] ?></div>
            <?php
        } ?>
        <div class="block>">
            <?php
            # If registration id is set, the user can delete his registration.
            if (isset($_GET["r"])) {
                $registration_id = filter_input(INPUT_GET, "r", FILTER_SANITIZE_ENCODED);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($registration_id)) {
                    deleteRegistration($registration_id, $E);
                } else {
                    register($E);
                }
            } else {
                if (isset($registration_id)) {
                    showDeleteRegistration($registration_id, $E);
                } else {
                    showRegistration($E);
                }
            }
            ?>
            <a href="index.php?lang=<?= $localizer->getLang() ?>">
                <div class="box icon arrow-left">
                    <p class="name"><?= $localizer['back'] ?></p>
                </div>
            </a>
        </div>
        <div class="block center">
            <a href="calender.php?e=<?= $event_id ?>&lang=<?= $localizer->getLang() ?>&ics"><?= $localizer['calender_download'] ?></a>
        </div>
    </div>
</div>
</body>
</html>
