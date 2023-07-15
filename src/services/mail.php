<?php

use PHPMailer\PHPMailer\PHPMailer;

class MailService
{
    public function Send($subject, $body, $to, $name = null)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = Config::$SMTP_HOST;
            $mail->Username = Config::$SMTP_USERNAME;
            $mail->Password = Config::$SMTP_PASSWORD;
            $mail->Port = Config::$SMTP_PORT;
            $mail->SMTPAuth = true;
            $mail->setFrom(Config::$SMTP_USERNAME, Config::$SMTP_FROM);
            $mail->CharSet = "UTF-8";

            // Recipients
            $mail->addAddress($to);

            // Content
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->msgHTML($body);

            $mail->send();
        } catch (FFI\Exception $e) {
            print_r("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}