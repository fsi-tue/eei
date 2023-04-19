<?php

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

// Loads the environment variables from the .env file
loadEnv('.env');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/**
 * Sends an email to a given address.
 *
 * @param        $recipient
 * @param string $subject
 * @param string $body
 *
 * @return bool
 */
function sendMailViaPHPMailer($recipient, $subject, $body): bool
{
    $mail = new PHPMailer(TRUE);
    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output. */
        $mail->isSMTP();

        /* https://stackoverflow.com/questions/2491475/phpmailer-character-encoding-issues */
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

        $mail->SMTPAuth = TRUE;
        $mail->SMTPKeepAlive = TRUE;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ];
        $mail->Host = getEnvVar('EMAIL_HOST');
        $mail->Port = getEnvVar('EMAIL_PORT');
        $mail->Password = getEnvVar('SENDER_PASSWORD');
        $mail->Username = getEnvVar('SENDER_USERNAME');
        $mail->setFrom(getEnvVar('SENDER_EMAIL'), getEnvVar('SENDER_NAME'));
        $mail->addAddress($recipient);

        $mail->isHTML();
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return TRUE;
    } catch (Exception $exception) {
        return FALSE;
    }
}