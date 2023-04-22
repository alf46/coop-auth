<?php

require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Auth
{
    // Generar un nuevo token de acceso
    public function generateToken($username, $email, $role)
    {
        $now = time();
        $expiration = $now + Config::$TOKEN_DURATION;
        $payload = array(
            "sub" => $username,
            "email" => $email,
            "role" => $role,
            "iat" => $now,
            "exp" => $expiration
        );

        return JWT::encode($payload, Config::$TOKEN_SECRET_KEY, 'HS512');
    }

    // Validar un token de acceso existente
    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key(Config::$TOKEN_SECRET_KEY, 'HS512'));
        } catch (Exception $e) {
            return false;
        }
    }
}