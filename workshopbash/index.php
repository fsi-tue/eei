<?php
require_once('../config.php');
$short = 'WSBS'; #Kürzel des Events
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
    <title><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            <?php echo $freeSpots;?>
            Workshop Bash Basics<br>
            Der Gedanke an unixartige Systeme und die Kommandozeile bereitet dir Unbehagen? Das muss nicht sein! <br>
            In diesem Workshop lernst du am Beispiel der Bourne-again-Shell, dich in einer POSIX Shell zu bewegen und grundlegende Dateioperationen durchzuführen.<br>
            Dies ist der Erste von zwei Teilen und behandelt die absoluten Grundlagen.<br>
            
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="info"><strong>Dieser Workshop ist für Studierende im 2. Semester oder höher gedacht!<br><br>Für die Veranstaltung gilt 3G</strong></div>
        <br>
        <div class="block>">
            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                    register($E, $meal);
                showRegistration($E, $meal);
            ?>
        </div>
    </div>
</body>
</html>
