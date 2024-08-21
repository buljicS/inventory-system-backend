<?php

namespace Utilities;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer as phpMailer;

class MailUtility
{
	private phpMailer $mailer;

	public function __construct()
	{
		$this->mailer = new PHPMailer(true);
	}

	public function SendEmail(string $body, string $subject, string $sendTo, ?string $ccTo): string
	{
		try {
			$this->mailer->isSMTP();
			$this->mailer->Host = $_ENV['EMAIL_HOST'];
			$this->mailer->SMTPAuth = true;
			$this->mailer->SMTPSecure = $_ENV['EMAIL_SMTP_SECURE'];
			$this->mailer->Port = $_ENV['EMAIL_PORT'];
			$this->mailer->Username = $_ENV['EMAIL_USERNAME'];
			$this->mailer->Password = $_ENV['EMAIL_PASSWORD'];

			$this->mailer->setFrom($_ENV['EMAIL_FROM'], $_ENV['EMAIL_FROM_USR']);
			$this->mailer->addAddress($sendTo);

			if($ccTo != null) {
				$this->mailer->addCC($ccTo);
			}

			$this->mailer->Subject = $subject;
			$this->mailer->Body = $body;
			$this->mailer->isHTML(true);
			$this->mailer->AltBody = strip_tags($body);

			$this->mailer->send();
			$this->mailer->clearAddresses();
			return 'OK';
		} catch (Exception $e){
			return $e->getMessage();
		}
	}

}