<?php

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

// Loads the environment variables from the .env file
loadEnv('.env');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Sends an email to a given address.
 *
 * @param string $recipient
 * @param string $subject
 * @param string $body
 * @param string $attachment
 * @param string $attachment_name
 * @return bool
 */
function sendMailViaPHPMailer(string $recipient, string $subject, string $body, string $attachment = '', string $attachment_name = ''): bool
{
    $mail = new PHPMailer(TRUE);
    try {
        $mail->isSMTP();

        /* https://stackoverflow.com/questions/2491475/phpmailer-character-encoding-issues */
        $mail->Encoding = 'base64';
        $mail->CharSet = 'UTF-8';

        $mail->SMTPAuth = FALSE;
        $mail->SMTPKeepAlive = TRUE;
        $mail->SMTPSecure = FALSE;
        $mail->SMTPAutoTLS = FALSE;

        // Set variables for local development
        if (getEnvVar('LOCAL') === 'true') {
            $mail->SMTPDebug = 1;
            $mail->SMTPAuth = TRUE;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                ]
            ];
            $mail->Password = getEnvVar('SENDER_PASSWORD');
            $mail->Username = getEnvVar('SENDER_USERNAME');
        }

        $mail->Host = getEnvVar('EMAIL_HOST');
        $mail->Port = getEnvVar('EMAIL_PORT');

        $mail->setFrom(getEnvVar('SENDER_EMAIL'), getEnvVar('SENDER_NAME'));
        $mail->addAddress($recipient);

        $mail->isHTML();
        $mail->Subject = $subject;
        $mail->Body = $body;

        // If an attachment is given, add it to the email. 
        if ($attachment !== '') {
            $mail->addStringAttachment($attachment, $attachment_name);
        }

        $mail->send();
        return TRUE;
    } catch (Exception) {
        return FALSE;
    }
}
