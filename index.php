<?php
require_once('config.php');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Anfi Veranstaltungen</title>
</head>
<body>
    <!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
    <!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
    <div id="center">
        <h1>Anfi-Veranstaltungen - <?php echo $CONFIG_TERM?></h1>
        <div class="container">
        <?php 
        foreach ($events as $e) {
                echo "<a href='{$e['link']}'>";
                echo "  <div class='box icon {$e['icon']}'>";
                echo "     <p class='name'>{$e['name']}</p>";
                echo "     <p class='date'>{$e['date']}</p>";
                echo "  </div>";
                echo "</a>";
        }
?>
        </div>
    </div>
</body>
</html>
