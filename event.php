<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'i18n/i18n.php';
require_once 'event_type.php';

// global variables
global $i18n, $events, $CONFIG_TERM, $FILE_REVISION, $CONFIG_CONTACT;

// Apply different checks to avoid loading the page, e.g., if the event is not active
const LOCATION = 'Location:/';
// If event id is not set, redirect to main page.
if (!isset($_GET["e"])) {
	header(LOCATION);
	die();
}

// Get event id
$event_id = filter_input(INPUT_GET, "e", FILTER_SANITIZE_ENCODED);

if (!isset($events[$event_id])) {
	// Redirect to main page
	header(LOCATION);
	die();
}
/* @var Event $event */
$event = $events[$event_id];

?>
<!DOCTYPE html>
<html lang="<?= $i18n->getLanguage() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/input.css<?= $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/icons.css<?= $FILE_REVISION; ?>">
    <title><?= $event->name . " - " . $CONFIG_TERM ?></title>
</head>

<body>
<div id="center" class="small">
    <h1><?= $event->name . " - " . $CONFIG_TERM ?></h1>
    <h2 class="description icon clock"><?= $event->getEventDateString() ?></h2>
    <h2 class="description icon marker"><?= $event->location; ?></h2>
    <h2 class="description"><?= $i18n['remaining'] . ": " . $event->getRemainingSpots() ?></h2>
	<?php if($event->dinosAllowed) {?> <h4 class="description"> <?= $i18n['dinos'] ?></h4> <?php } ?>
	<?= $event->text ?>
    <br>
	<?php
	if ($event->info != '') {
		?>
        <div class="text-block info"><?= $event->info ?></div>
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
			$del_register_ret = deleteRegistration($registration_id, $event);
			?>
            <!-- Print the result of the deletion -->
            <div class="text-block <?= $del_register_ret[0] ? 'info' : 'error' ?>">
				<?= $del_register_ret[1] ?>
            </div>
			<?php
		} else {
			// Register for event
			$register_ret = register($event);
			?>
            <div class="text-block <?= $register_ret[0] ? 'info' : 'error' ?>">
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
            <form action="event.php?e=<?= $event->link ?>&r=<?= $registration_id ?>&lang=<?= $i18n->getLanguage() ?>"
                  method="post">
                <div class="text-block">
					<?= $i18n->translate('unsubscribe_text') ?>
                </div>
                <input type="hidden" name="registration_id" value="<?= $registration_id ?>">
                <input type="submit" name="delete_registration" value="<?= $i18n->translate('unsubscribe') ?>">
            </form>
		<?php
		} else {

		// THIS IS NOT A LOOP!
		// It is used to break out of the loop if an error occurs
		do {
		// Check for possible errors and print them if necessary
		$time = time();
		if (!$event->canRegister()) {
		?>
            <div class="text-block error">
				<?php
				// These are sorted by priority
				if ($event->cancelled) {
					// Event is cancelled
					// This has obviously the highest priority and should be displayed first
					echo $i18n->translate('event_cancelled', array('EVENT_NAME' => $event->name, 'EMAIL_CONTACT' => $CONFIG_CONTACT));
				} elseif ($event->getRemainingSpots() == 0) {
					// Event is full
					echo $i18n['event_full'];
				} elseif (!$event->isUpcoming()) {
					echo $i18n['event_is_past'];
				} elseif ($time < $event->getRegistrationStartUTS() && $event->getRegistrationStartUTS() > 0) {
					// Event is not yet open for registration
					echo $i18n->translate('start_of_registration', array('REGISTRATION_DATE' => $event->getRegistrationDateString()));
				} elseif ($time > $event->getRegistrationEndUTS()) {
					// Event is no longer open for registration
					echo $i18n['end_of_registration'];
				}
				?>
            </div>
		<?php break; // Break out of the 'if'-statement because there's an error!
		}
		?>

            <form method="post" action="#">
                <label for="form-name">
					<?= $i18n['form_yourName'] ?>:<br>
                    <input type="text" id="form-name" name="name" required size="30">
                </label>
                <br><br>

                <label for="form-mail">
					<?= $i18n['form_email'] ?>:<br>
                    <input type="email" id="form-mail" name="mail" required size="30">
                </label>
                <br><br>
				<?php

				// Courses
				if ($event->form['course_required']) {
					echo $i18n['form_study_programme'] . ':<br>';
					$courses = [
						['Informatik', 'form_cs'],
						['Lehramt', 'form_cs_ed'],
						['Bioinformatik', 'form_cs_bio'],
						['Medizininformatik', 'form_cs_med'],
						['Medieninformatik', 'form_cs_media'],
						['Maschinelles Lernen', 'form_ml'],
						['Kognitionswissenschaft', 'form_cog'],
						['Nebenfach', 'form_subsidiary']
					];
					foreach ($courses as $course) {
						?>
                        <label>
                            <input type="radio" class="form-studiengang" name="studiengang"
                                   value="<?= $course[0] ?>" required>
							<?= $i18n[$course[1]] ?>
                        </label>
                        <br>
					<?php } ?>

                    <br><?= $i18n['form_degree'] ?>:<br>
					<?php
					// Degree
					$degrees = ['Bachelor', 'Master'];
					foreach ($degrees as $degree) {
						?>
                        <label>
                            <input type="radio" class="form-abschluss" name="abschluss"
                                   value="<?= $degree ?>" required>
							<?= $degree ?>
                        </label>
                        <br>
					<?php } ?>

                    <br>Semester: <br>
					<?php
					// Semester
					$semesters = ['1', '2', '3', $i18n['form_many']];
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
				if ($event->form['food']) {
					echo $i18n['form_food'] . ':<br>';
					$food_preferences = [
						$i18n['form_food_no_preference'],
						$i18n['form_food_vegetarian'],
						$i18n['form_food_vegan'],
						$i18n['form_food_no_pork']
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
				if ($event->form['breakfast']) {
					echo $i18n['form_breakfast'] . ':<br>';
					$food_preferences = [
						$i18n['form_food_no_preference'],
						$i18n['form_food_sweet'],
						$i18n['form_food_salty'],
					];
					foreach ($food_preferences as $preference) {
						?>
                        <label>
                            <input type="radio" class="form-fruehstueck" name="fruestueck" value="<?= $preference ?>"
                                   required>
							<?= $preference ?>
                        </label>
                        <br>
					<?php }
				} ?>
                <input type="submit" value="<?= $i18n['send'] ?>" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="js/saveFormValues.js"></script>
			<?php
		} while (false); // Close the non-executing loop
		}
		}
		?>
        <div class="container">
            <a href="index.php?lang=<?= $i18n->getLanguage() ?>">
                <div class="link">
					<?= $i18n['back'] ?>
                </div>
            </a>

            <a href="calender.php?e=<?= $event_id ?>&lang=<?= $i18n->getLanguage() ?>&ics">
                <div class="link color-border">
					<?= $i18n['calender_download'] ?>
                </div>
            </a>
        </div>
    </div>
</div>
</body>

</html>
