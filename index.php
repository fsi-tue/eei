<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n, $CONFIG_TERM, $FILE_REVISION, $events;

// Load environment variables from the .env file
loadEnv('.env');

// Ensure the eei-registration folder exists
createEeiRegistrationFolder();
?>

<!DOCTYPE html>
<html lang="<?= $i18n->getLanguage() ?>">

<?php
require_once 'head.php';
?>

<body>
<!-- Icons courtesy of fontawesome.com under CC BY 4.0 License -->
<!-- BBQ-Grill icon by Smashicons from flaticon.com -->
<div id="center">
    <div class="container">
        <span class="sub-title"><?= $CONFIG_TERM ?></span>
        <h1 class="title">ERSTI-PROGRAMM</h1>
    </div>

    <div class="container">
        <div class="language-switcher">
            <label for="lang-selection" style="display: none;">Language</label> <!-- Hidden label for accessibility -->
            <select id="lang-selection" aria-label="Select language">
                <option value="de" <?= $i18n->getLanguage() === 'de' ? 'selected' : '' ?>>🇩🇪 Deutsch</option>
                <option value="en" <?= $i18n->getLanguage() === 'en' ? 'selected' : '' ?>>🇬🇧 English</option>
            </select>
        </div>
    </div>

    <div class="container">
        <?php
        // Sort events by activity status and date
        $sorted_events = $events;
        usort($sorted_events, function (Event $a, Event $b) {
            // Keep future events before past events
            if ($a->isPast() && !$b->isPast()) {
                return 1; // Past events appear after active ones
            } elseif (!$a->isPast() && $b->isPast()) {
                return -1;
            }
            // If both are past or both are future, sort by date
            return $a->getEventStartUTS() - $b->getEventStartUTS();
        });

        // Render the sorted events
        foreach ($sorted_events as $event) {
            $fmt = datefmt_create($i18n->getLanguage(), IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, NULL, NULL, 'dd.MM.');
            $date_string = datefmt_format($fmt, $event->getEventStartUTS());

            ?>
            <a href="event.php?e=<?= $event->link ?>&lang=<?= $i18n->getLanguage() ?>" class="event-link">
                <div class="event-row <?= $event->isPast() ? 'past' : '' ?> <?= $event->eventIsTakingPlace() ? 'today' : '' ?>">
                    <div class="event-pill event-date-pill">
                        <?= $event->getEventDateString(['compact' => true, 'no_time' => true]) ?>
                    </div>
                    <div class="event-pill event-info-pill">
                        <span class="event-name"><?= htmlspecialchars($event->name) ?></span>
                        <?php
                        // Render icon using a span and the class from $event->icon ?>
                        <?php
                        if (!empty($event->icon)): ?>
                            <span class="event-icon icon <?= htmlspecialchars($event->icon) ?>"></span>
                        <?php
                        endif; ?>
                    </div>
                </div>
            </a>
            <?php
        } ?>
    </div>

    <br>
    <div class="footnotes">
        <p><?= $i18n['index_savedDataDisclaimer'] ?></p>
        <br>
        <!-- Using a container with row direction for buttons -->
        <div class="container">
            <div class="row">
                <input id="btn-clr" type="submit" value="<?= $i18n['delete'] ?>"
                       onclick="if(confirm('<?= addslashes($i18n['index_confirmDelete']) ?>')) { localStorage.clear(); alert('<?= addslashes($i18n['index_deletedData']) ?>'); }">

                <a href="https://github.com/fsi-tue/eei">
                    <!-- Removed inner div, styling applied directly to link -->
                    <span class="link"> <!-- Use span or div if needed, styled by .link -->
                        Source Code auf GitHub
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Language change handler
    document.getElementById('lang-selection').addEventListener('change', function () {
        // Construct URL without query string first
        let baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        // Add the new language parameter
        location.href = `${baseUrl}?lang=${this.value}`;
    });
</script>
</body>

</html>