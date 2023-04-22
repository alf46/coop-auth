<?php interface IAuthService
{
    public function Login($username, $password);
    public function Forgot($username);
    public function Recovery($forgot_code, $password);
}