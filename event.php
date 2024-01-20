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
if (!$E['active']) {
    // Event is not active
    // Redirect to main page
    header("Location:/");
    die();
}

?>
<!DOCTYPE html>
<html lang="<?= $localizer->getLang() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/input.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/icons.css<?= $FILE_REVISION; ?>">
    <title><?= $E['name'] . " - " . $CONFIG_TERM ?></title>
</head>

<body>
    <div id="center" class="small">
        <h1><?= $E['name'] . " - " . $CONFIG_TERM ?></h1>
        <h2 class="description icon clock"><?= showDateAndTime($E) ?></h2>
        <h2 class="description icon marker"><?= $E['location']; ?></h2>
        <h2 class="description"><?= $localizer['remaining'] . ": " . getNumberOfRemainingSpots($E) ?></h2>
        <?= $E["text"] ?>
        <br>
        <?php
        if ($E['info'] != '') {
        ?>
            <div class="block info"><?= $E["info"] ?></div>
        <?php
        } ?>
        <div class="block>">
            <?php

            // Check if a registration id is given
            if (isset($_GET["r"])) {
                $registration_id = filter_input(INPUT_GET, "r", FILTER_SANITIZE_ENCODED);
            }

            // Check if it is a POST request
            // POST requests are used to register for an event or to delete a registration
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //////////////////////////////////////////
                // REGISTRATION OR UNSUBSCRIPTION PROCESSING

                // Registration id is used to find the registration in the csv file
                if (isset($registration_id)) {
                    // Delete registration
                    $del_register_ret = deleteRegistration($registration_id, $E);
            ?>
                    <!-- Print the result of the deletion -->
                    <div class="block <?= $del_register_ret[0] ? 'info' : 'error' ?>">
                        <?= $del_register_ret[1] ?>
                    </div>
                <?php
                } else {
                    // Register for event
                    $register_ret = register($E);
                ?>
                    <div class="block <?= $register_ret[0] ? 'info' : 'error' ?>">
                        <?= $register_ret[1] ?>
                    </div>
                <?php
                }
            } else {
                //////////////////////////////////////////
                // REGISTRATION AND UNSUBSCRIPTION FORM

                // Check if a registration id is given
                if (isset($registration_id)) {
                ?>
                    <form action="event.php?e=<?= $E['link'] ?>&r=<?= $registration_id ?>&lang=<?= $localizer->getLang() ?>" method="post">
                        <div class="block info">
                            <?= $localizer->translate('unsubscribe_text') ?>
                        </div>
                        <input type="hidden" name="registration_id" value="<?= $registration_id ?>">
                        <input type="submit" name="delete_registration" value="<?= $localizer->translate('unsubscribe') ?>">
                    </form>
                    <?php
                } else {

                    // THIS IS NOT A LOOP!
                    // It is used to break out of the loop if an error occurs
                    do {
                        // Check for possible errors and print them if necessary
                        $time = time();
                        if ($time < $E['start_of_registration'] || $time >= $E['end_of_registration'] || $E['cancelled'] || !$E['active'] || getNumberOfRemainingSpots($E) == 0) {
                    ?>
                            <div class="block error">
                                <?php
                                // These are sorted by priority
                                if ($E['cancelled']) {
                                    // Event is cancelled
                                    // This has obviously the highest priority and should be displayed first
                                    echo $localizer->translate('event_cancelled', array('EVENT_NAME' => $E['name'], 'EMAIL_CONTACT' => $CONFIG_CONTACT));
                                } else if (getNumberOfRemainingSpots($E) == 0) {
                                    // Event is full
                                    echo $localizer['event_full'];
                                } else if ($time < $E['start_of_registration']) {
                                    // Event is not yet open for registration
                                    echo $localizer['start_of_registration'];
                                } else if ($time >= $E['end_of_registration']) {
                                    // Event is no longer open for registration
                                    echo $localizer['end_of_registration'];
                                }
                                ?>
                            </div>
                        <?php break; // Break out of the 'if'-statement because there's an error!
                        }
                        ?>

                        <form method="post" action="#">
                            <label for="form-name">
                                <?= $localizer['form_yourName'] ?>:<br>
                                <input type="text" id="form-name" name="name" required size="30">
                            </label>
                            <br><br>

                            <label for="form-mail">
                                <?= $localizer['form_email'] ?>:<br>
                                <input type="email" id="form-mail" name="mail" required size="30">
                            </label>
                            <br><br>
                            <?php

                            // Courses
                            if ($E['course_required']) {
                                echo $localizer['form_study_programme'] . ':<br>';
                                $courses = [
                                    ['Informatik', 'form_cs'],
                                    ['Lehramt', 'form_cs_ed'],
                                    ['Bioinformatik', 'form_cs_bio'],
                                    ['Medizininformatik', 'form_cs_med'],
                                    ['Medieninformatik', 'form_cs_media'],
                                    ['Maschinelles Lernen', 'form_ml'],
                                    ['Kognitive Informatik', 'form_cog'],
                                    ['Nebenfach', 'form_subsidiary']
                                ];
                                foreach ($courses as $course) {
                            ?>
                                    <label>
                                        <input type="radio" class="form-studiengang" name="studiengang" value="<?= $course[0] ?>" required>
                                        <?= $localizer[$course[1]] ?>
                                    </label>
                                    <br>
                                <?php } ?>

                                <br><?= $localizer['form_degree'] ?>:<br>
                                <?php
                                // Degree
                                $degrees = ['Bachelor', 'Master'];
                                foreach ($degrees as $degree) {
                                ?>
                                    <label>
                                        <input type="radio" class="form-abschluss" name="abschluss" value="<?= $degree ?>" required>
                                        <?= $degree ?>
                                    </label>
                                    <br>
                                <?php } ?>

                                <br>Semester: <br>
                                <?php
                                // Semester
                                $semesters = ['1', '2', '3', $localizer['form_many']];
                                foreach ($semesters as $semester) {
                                ?>
                                    <label>
                                        <input type="radio" class="form-semester" name="semester" value="<?= $semester ?>" required>
                                        <?= $semester ?>
                                    </label>
                                    <br>
                                <?php } ?>
                                <br>
                                <?php
                            }

                            // Food
                            if ($E['food']) {
                                echo $localizer['form_food'] . ':<br>';
                                $food_preferences = [
                                    $localizer['form_food_no_preference'],
                                    $localizer['form_food_vegetarian'],
                                    $localizer['form_food_vegan'],
                                    $localizer['form_food_no_pork']
                                ];
                                foreach ($food_preferences as $preference) {
                                ?>
                                    <label>
                                        <input type="radio" class="form-essen" name="essen" value="<?= $preference ?>" required>
                                        <?= $preference ?>
                                    </label>
                                    <br>
                            <?php }
                            }
                            ?>
                            <br>

                            <?php
                            // Breakfast
                            if ($E['breakfast']) {
                                echo $localizer['form_breakfast'] . ':<br>';
                                $food_preferences = [
                                    $localizer['form_food_no_preference'],
                                    $localizer['form_food_sweet'],
                                    $localizer['form_food_salty'],
                                ];
                                foreach ($food_preferences as $preference) {
                            ?>
                                    <label>
                                        <input type="radio" class="form-fruehstueck" name="fruestueck" value="<?= $preference ?>" required>
                                        <?= $preference ?>
                                    </label>
                                    <br>
                            <?php }
                            } ?>
                            <input type="submit" value="<?= $localizer['send'] ?>" onclick="saveFormValues()">
                        </form>
                        <script type="text/javascript" src="js/saveFormValues.js"></script>
            <?php
                    } while (False); // Close the non-executing loop
                }
            }
            ?>
            <div class="container left">
                <a href="index.php?lang=<?= $localizer->getLang() ?>">
                    <div class="link">
                        <?= $localizer['back'] ?>
                    </div>
                </a>

                <a href="calender.php?e=<?= $event_id ?>&lang=<?= $localizer->getLang() ?>&ics">
                    <div class="link color-border">
                        <?= $localizer['calender_download'] ?>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>

</html>