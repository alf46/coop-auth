<?php interface IMailService
{
    public function Send($subject, $body, $email, $name = null);
}