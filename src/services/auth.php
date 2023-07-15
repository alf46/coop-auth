<?php

use Ramsey\Uuid\Uuid;

class AuthService
{
    private UserService $us;

    function __construct()
    {
        $this->us = new UserService();
    }

    public function Login($username, $password)
    {
        // Get user from db
        $user = $this->us->GetWithPassword($username);

        // Valid user data
        $hasher = new PasswordHasher();
        if ($hasher->validatePassword($password, $user->password)) {
            // Generate token
            $auth = new Auth();
            $access_token = $auth->generateToken($user->username, $user->email, $user->role);
            return array('access_token' => $access_token);
        }

        throw new Exception("login.failed", 401);
    }

    public function Forgot($username)
    {
        // Get user from db
        $user = $this->us->Get($username, true);
        $id = $this->createForgotCode($user->username, $user->email);

        // Send email with reset code
        $link = Config::$ADMIN_SITE_URL . "/recuperacion?code={$id}";

        // Buscar la platilla hmtl y reemplazar el link.
        $contents = file_get_contents("./templates/forgot.html");
        $contents = str_replace(array('{{link}}'), array($link), $contents);

        $ms = new MailService();
        $ms->Send("Recuperación de contraseña", $contents, $user->email);

        return array("email" => $user->email);
    }

    public function Recovery($forgot_code, $password)
    {
        // Validate and get ForgotCode from db
        $username = $this->getForgotCode($forgot_code);

        // Update user passowrd
        $this->us->UpdatePassword($username, $password);

        // Set to used token
        $this->updateForgotCode($forgot_code);
    }

    public function ChangePassword($currentPassword, $newPassword)
    {
        $username = $_SESSION['sub'];
        $user = $this->us->GetWithPassword($username);

        // Validate current password
        $hasher = new PasswordHasher();
        if ($hasher->validatePassword($currentPassword, $user->password)) {
            $this->us->UpdatePassword($username, $newPassword);
        } else {
            throw new Exception("invalid current password", 400);
        }
    }

    private function createForgotCode($username, $email)
    {
        global $db;

        $id = Uuid::uuid4();
        $exp = time() + Config::$FORGOT_DURATION;

        $query = "INSERT INTO `reset_password` (`id`, `username`, `email`, `exp`) VALUES (?,?,?,?)";
        $result = $db->Execute($query, array($id, $username, $email, $exp));

        if ($result) {
            return $id;
        }

        throw new Exception("error.create.reset", 500);
    }

    private function getForgotCode($forgot_code): string
    {
        global $db;
        $query = "SELECT `id`, `username` FROM `reset_password` WHERE `id`=? AND `exp`>? AND `used`=0";
        $db->Execute($query, array($forgot_code, time()));
        if ($db->NumRows() > 0) {
            $data = $db->FetchRow();
            return $data["username"];
        }

        throw new Exception("invalid.forgot.code", 400);
    }

    private function updateForgotCode($forgot_code)
    {
        global $db;
        $query = "UPDATE `reset_password` SET `used`=0 WHERE `id`=?";
        $result = $db->Execute($query, array($forgot_code));

        if (!$result) {
            throw new Exception("error.update.forgot", 500);
        }
    }
}