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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title> <?php echo "{$E['name']} - $CONFIG_TERM"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?= "{$E['name']} - $CONFIG_TERM"; ?></h1>
            <h2 class="description icon clock"><?= showDateAndTime($E['startUTS'], $E['endUTS'], array('onTime' => $E['onTime'])) ?></h2>
            <h2 class="description icon marker"><?= $E['location'];?></h2>
            <h2 class="description"><?= $localizer['remaining'] . ": " . getNumberOfRemainingSpots($E) ?></h2>
            <?php
echo $E["text"];
echo "<br>";
echo ($E['info'] == '' ? '' : "<div class='block info'>{$E['info']}</div>");
echo '<div class="block>">';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    register($E);
}
else {
    showRegistration($E);
}
?>

        </div>
    </div>
</body>
</html>
