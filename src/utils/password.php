<?php class PasswordHasher
{
    // Genera un hash de la contraseña usando bcrypt
    public function hashPassword($password)
    {
        /* El costo del hashing. 
        Un mayor costo aumenta la seguridad pero también el tiempo de procesamiento.*/
        $options = ['cost' => 12];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    // Valida si la contraseña coincide con el hash almacenado
    public function validatePassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}