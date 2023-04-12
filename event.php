<?php
require_once('config.php');
require_once('utils.php');
require_once('event_data.php');

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
    <title> <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
<?php 
echo "<h2 class=\"description\">Verbleibende Plätze:".(string)(getNumberOfRemainingSpots($E))."</h2>";
echo $E["text"];
echo "<br>";
echo ($E['info'] == '' ? '' : "<div class='block info'>{$E['info']}</div>");
echo '<div class="block>">';

# If registration id is set, the user can delete his registration.
if (isset($_GET["r"])) {
    $registration_id = filter_input(INPUT_GET, "r", FILTER_SANITIZE_ENCODED);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($registration_id)) {
        deleteRegistration($registration_id, $E);
    } else {
        register($E);
    }
}
else {
    if (isset($registration_id)) {
        showDeleteRegistration($registration_id, $E);
    } else {
        showRegistration($E);
    }
}
?>

        </div>
    </div>
</body>
</html>
