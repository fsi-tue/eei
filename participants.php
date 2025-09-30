<?php
declare(strict_types=1);

require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n;

class ParticipantListMailer
{
    private const MAX_TIME_BETWEEN_EMAILS = 2 * 60 * 60; // 2 hours
    private string $logPath;

    public function __construct(string $logPath)
    {
        $this->logPath = $logPath;
    }

    public function sendParticipantList(Event $event): string
    {
        $emailAddresses = $event->metas;

        if (!$this->canSendEmail($event) || empty($emailAddresses)) {
            return "Der letzte Mailversand liegt unter der Minimalzeit, bitte versuch es später erneut!";
        }

        if (!$this->validateEmailAddresses($emailAddresses)) {
            return "Eine der hinterlegten Mailadressen ist keine gültige Mailadresse!";
        }

        $participants = $this->getParticipants($event);
        $emailContent = $this->buildEmailContent($event, $participants);

        if (!$this->sendEmails($emailAddresses, $emailContent['subject'], $emailContent['body'])) {
            return "Der Mailversand hat nicht funktioniert!";
        }

        $this->logEmailSent($event, $emailAddresses);
        return "Die Teilnehmerliste wurde erfolgreich versendet.";
    }

    private function getParticipants(Event $event): array
    {
        global $CSV_OPTIONS;
        if (!file_exists($event->csvPath)) {
            return [];
        }

        $participants = [];
        if (($handle = fopen($event->csvPath, 'r')) !== FALSE) {
            // Skip header row
            fgetcsv($handle, null, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape']);

            while (($data = fgetcsv($handle, null, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape'])) !== FALSE) {
                $participants[] = [
                    'name' => $data[0] ?? '',
                    'mail' => $data[1] ?? '',
                    'misc' => implode(', ', array_slice($data, 2))
                ];
            }
            fclose($handle);
        }

        return $participants;
    }

    private function buildEmailContent(Event $event, array $participants): array
    {
        $subject = "Teilnehmerliste für {$event->name} am " . $event->getEventDateString();
        $body = "$subject:<br><br>";

        foreach ($participants as $participant) {
            $body .= sprintf(
                '%s (<a href="mailto:%s">%s</a>) %s<br>',
                htmlspecialchars($participant['name']),
                $participant['mail'],
                $participant['mail'],
                htmlspecialchars($participant['misc'])
            );
        }

        $emailList = implode(',', array_column($participants, 'mail'));
        $body .= "<br><br>Mail-Liste:<br><br>$emailList";

        return [
            'subject' => $subject,
            'body' => $body
        ];
    }

    private function canSendEmail(Event $event): bool
    {
        global $CSV_OPTIONS;
        if (!file_exists($this->logPath)) {
            return TRUE;
        }

        $lastSentTime = 0;
        if (($handle = fopen($this->logPath, 'r')) !== FALSE) {
            while (($line = fgetcsv($handle, null, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape'])) !== FALSE) {
                if ($line[0] === $event->link) {
                    $lastSentTime = strtotime($line[1] ?? '');
                }
            }
            fclose($handle);
        }

        return (time() - $lastSentTime) > self::MAX_TIME_BETWEEN_EMAILS;
    }

    private function logEmailSent(Event $event, array $emailAddresses): void
    {
        global $CSV_OPTIONS;
        $data = [
            $event->link,
            date('Y-m-d H:i:s'),
            implode(', ', $emailAddresses)
        ];

        if (($handle = fopen($this->logPath, 'a')) !== FALSE) {
            fputcsv($handle, $data, $CSV_OPTIONS['separator'], $CSV_OPTIONS['enclosure'], $CSV_OPTIONS['escape']);
            fclose($handle);
        }
    }

    private function validateEmailAddresses(array $emails): bool
    {
        return array_reduce($emails, fn($valid, $email) => $valid && filter_var($email, FILTER_VALIDATE_EMAIL), TRUE);
    }

    private function sendEmails(array $recipients, string $subject, string $body): bool
    {
        foreach ($recipients as $recipient) {
            if (!sendMailViaPHPMailer($recipient, $subject, $body)) {
                return FALSE;
            }
        }
        return TRUE;
    }
}

final class SecurityToken
{
    private const TOKEN_LENGTH = 32;

    public static function generate(): string
    {
        try {
            return bin2hex(random_bytes(self::TOKEN_LENGTH));
        } catch (Exception) {
            return '';
        }
    }
}

// Start the session and handle the request
session_start();

$mailer = new ParticipantListMailer($GLOBALS['fp'] . 'logs.csv');
$filtered_events = array_filter($GLOBALS['events'], fn(Event $event) => $event->isUpcoming() && $event->isRegistrationEnabled());

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['token'] = SecurityToken::generate();
    $_SESSION['token_field'] = SecurityToken::generate();
}

?>
<!DOCTYPE html>
<html lang="de">
<?php
require_once 'head.php';
?>
<body>
<main>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET'): ?>
            <form action="participants.php" method="post">
                <label for="event">Event:</label>
                <select name="event" id="event">
                    <?php
                    foreach ($filtered_events as $event): ?>
                        <option value="<?= htmlspecialchars($event->link) ?>">
                            <?= htmlspecialchars($event->name) ?> -
                            <?= htmlspecialchars($event->getEventDateString()) ?> -
                            <?= htmlspecialchars($event->link) ?>
                        </option>
                    <?php
                    endforeach; ?>
                </select>
                <br>
                <input type="hidden" name="send" value="true">
                <input type="hidden" name="<?= htmlspecialchars($_SESSION['token_field']) ?>"
                       value="<?= htmlspecialchars($_SESSION['token']) ?>">
                <br>
                <input type="submit" value="Liste senden">
            </form>

            <div class="container">
                <a href="index.php?lang=<?= $i18n->getLanguage() ?>">
                    <div class="link"><?= $i18n['back'] ?></div>
                </a>
            </div>
        <?php
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="container">
                <?php
                if (isset($_POST["send"], $_POST["event"],
                        $_POST[$_SESSION["token_field"]],
                        $_SESSION["token"]) &&
                    $_POST[$_SESSION["token_field"]] === $_SESSION["token"]) {

                    $link = filter_input(INPUT_POST, 'event', FILTER_SANITIZE_ENCODED);
                    $event = $GLOBALS['events'][$link];
                    $result = $mailer->sendParticipantList($event);
                    $isSuccess = $result === "Die Teilnehmerliste wurde erfolgreich versendet.";
                    ?>
                    <div class="text-block <?= $isSuccess ? '' : 'error' ?>">
                        <?= htmlspecialchars($result) ?>
                    </div>
                <?php
                } else { ?>
                    <div class="text-block error">Ungültiger Vorgang.</div>
                <?php
                } ?>

                <div class="container">
                    <a href="participants.php?lang=<?= $i18n->getLanguage() ?>">
                        <div class="link"><?= $i18n['back'] ?></div>
                    </a>
                </div>
            </div>
        <?php
        endif; ?>
    </div>
</main>
</body>
</html>