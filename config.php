<?php class Config
{
    // Clave secreta para firmar y validar el token
    public static $TOKEN_SECRET_KEY = "2948209dfsdj#*@&#@JD(@)#&@)&@#*(@";

    // Duración del token en segundos (1 dia).
    public static $TOKEN_DURATION = 86400;

    // Duración del token de recuperación de contraseña (5 minutos).
    public static $FORGOT_DURATION = 3000;

    // Configuracion de la base de datos
    public static $DATABASE_NAME = "coop-auth";
    public static $DATABASE_HOST = "db-coop-auth";
    public static $DATABASE_USERNAME = "admin";
    public static $DATABASE_PASSWORD = "admin";
    public static $DATABASE_PORT = 3306;

    // Configuracion SMTP.
    public static $SMTP_PASSWORD = "password";
    public static $SMTP_HOST = "smtp-server";
    public static $SMTP_USERNAME = "coop@coopincuba.com";
    public static $SMTP_FROM = "Coopincuba";
    public static $SMTP_PORT = 2500;
}