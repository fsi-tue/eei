<?php

require __DIR__ . '/lib/phpmailer/src/Exception.php';
require __DIR__ . '/lib/phpmailer/src/PHPMailer.php';
require __DIR__ . '/lib/phpmailer/src/SMTP.php';

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
	$mail = new PHPMailer(true);
	try {
		$mail->isSMTP();

		/* https://stackoverflow.com/questions/2491475/phpmailer-character-encoding-issues */
		$mail->Encoding = 'base64';
		$mail->CharSet = 'UTF-8';

		if (isLocalhost()) {
			// If the server is localhost, use SMTP authentication
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->SMTPOptions = [
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				]
			];
			$mail->Password = getEnvVar('SENDER_PASSWORD');
			$mail->Username = getEnvVar('SENDER_USERNAME');
		} else {
			// Otherwise, use no authentication
			$mail->SMTPAuth = false;
			$mail->SMTPSecure = false;
			$mail->SMTPAutoTLS = false;
		}
		$mail->SMTPKeepAlive = true;

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
		return true;
	} catch (Exception) {
		if (isLocalhost()) {
			echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
		}
		return false;
	}
}
