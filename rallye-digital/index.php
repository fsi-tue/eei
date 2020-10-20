<?php
require_once('../config.php');
require_once('../registration.php');
$short = 'RD'; #Kürzel des Events
$E = $events[$short]; #select Event
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css<?php echo $FILE_REVISION; ?>">
    <title>Anfi  <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            Die digitale Anfi Stadtrallye<br>
            Bei der digitalen Stadtrallye lassen wir dich und deine Kommilitonen gegeneinander in Teams antreten.
            Dabei werdet ihr online noch mehr interessante, schöne und verstörende Ecken Tübingens kennen lernen,
            dabei hoffentlich die Orientierung in eurer neuen Heimat weiter verbessern und Kontakte verbessern.<br>
            Der Link zur digitalen Stadtralley erhaltet ihr nach Anmeldung per Mail.<br>
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.<br><br>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    registerForOnlineEvent($E);

                showOnlineRegistration($E);
            ?>
        </div>
    </div>
</body>
</html>
