<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('send_email')) {
    function send_email($mail_config)
    {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'untamedandromeda@gmail.com';
        $mail->Password = 'keitdbqrlqsjkgep';
        $mail->SMTPSecure = 'TLS';
        $mail->Port = 587;
        $mail->setFrom($mail_config['mail_from_email'], $mail_config['mail_from_name']);
        $mail->addAddress($mail_config['mail_recipient_email'], $mail_config['mail_recipient_name']);
        $mail->isHTML(true);
        $mail->Subject = $mail_config['mail_subject'];
        $mail->Body = $mail_config['mail_body'];

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }
}
