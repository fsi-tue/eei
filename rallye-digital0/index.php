<?php
require_once('../config.php');
$short = 'RD0'; #Kürzel des Events
$meal = false;
$info = $error = '';
$E = $events[$short]; #select Event
$freeSpots = getNumberOfRemainingSpots($events[$short]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css<?php echo $FILE_REVISION; ?>">
    <title>Anfi <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            <?php echo $freeSpots;?>
            Die digitale Anfi Stadtrallye<br>
            Bei der digitalen Stadtrallye lassen wir dich und deine Kommilitonen gegeneinander in Teams antreten.
            Wir haben Videos zu Sehenswürdigkeiten in Tübingen aufgenommen und spannende Rätsel vorbereitet.
            Dabei werdet ihr online noch mehr schöne und interessante Ecken Tübingens kennen lernen,
            dabei hoffentlich die Orientierung in eurer neuen Heimat weiter verbessern und neue Kontakte knüpfen.<br>
            Den Link zum Discord-Server, auf dem die digitale Stadtralley stattfindet und die Gruppenzuteilung erhaltet ihr nach Anmeldung per Mail.<br><br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    register($E, $short, $meal);
                showRegistration($E, $meal);
            ?>
        </div>
    </div>
</body>
</html>
