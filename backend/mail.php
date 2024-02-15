<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';




    $phpmailer = new PHPMailer(true);

    try {

        $phpmailer->isSMTP();
        $phpmailer->Host = 'mail.programatori.stud.vts.su.ac.rs';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'programatori';
        $phpmailer->Password = '4sqwbd4SfhJhvCL';

        $phpmailer->setFrom('webmaster@example.com', 'Webmaster');
        $phpmailer->addAddress("filipkujundzic3@gmail.com");

        $phpmailer->isHTML(true);
        $phpmailer->Subject = "Subject";
        $phpmailer->Body = "This is the message body.";
        $phpmailer->AltBody = "This is the alt body.";

        $phpmailer->send();

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        $message = "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
