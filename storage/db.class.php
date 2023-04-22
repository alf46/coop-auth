<?php class mysql
{
    var $conexion;
    var $fechmode = PDO::FETCH_ASSOC;
    var $error;
    var $sql_query;
    var $sql_array;
    var $sql_row;

    //Opciones de conexion mysql:
    /*
    PDO::ATTR_PERSISTENT => false: No poner la conexion persistente, puede crear un problema en futuros escenarios
    PDO::ATTR_EMULATE_PREPARES => false: Desactivar la emulacion de consultas preparadas, forzando el uso real de consultas preparadas (previene SQL Inyection)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION: Escribir en el log los errores (usarlo debidamente para no revelar en el log la contraseña
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'": Establece el juego de caracteres utf-8, evita caracteres extraños en pantalla y previene SQL Inyection
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true: por defecto es true, poner false para una consulta con una gran cantidad de registros, asi en lugar de pasarse a PHP esta se queda en la cache de MySQL para ser iterada
    )
    */
    function Connect($emulate = false, $buffered = true)
    {
        $dsn = '';
        $pwd = '';
        $usr = '';

        // conectarse a la db
        $dsn = 'mysql:host=' . Config::$DATABASE_HOST . ';dbname=' . Config::$DATABASE_NAME . '';
        $pwd = Config::$DATABASE_PASSWORD;
        $usr = Config::$DATABASE_USERNAME;

        $options = array(
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_EMULATE_PREPARES => $emulate,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => $buffered
        );

        try {
            $this->conexion = new PDO($dsn, $usr, $pwd, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }

        return $this->conexion;
    }

    function ErrorInfo()
    {
        return $this->error;
    }

    function Execute($sql, $array = NULL)
    {
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode($this->fechmode);
        $retorna = false;
        $err = '';

        try {
            $retorna = $consulta->execute($array);
        } catch (PDOException $e) {
            $err = $e->getMessage();
        }

        if (!$retorna) {
            $errorinfo = $consulta->ErrorInfo();
            //if(conf_debug){
            //print_r($errorinfo);

            if ((int) $errorinfo[2] == 1172) {
                print_r($array);
            }
        } else {
            $this->sql_query = $consulta;
            return $retorna;
        }
    }

    function FetchRow()
    {
        $a = $this->sql_query->fetch();
        return $a;
    }

    function NumRows()
    {
        return $this->sql_query->rowCount();
    }

    //Ultimo ID insertado
    function LastInsertID()
    {
        return $this->conexion->lastInsertId();
    }

    function GetArray()
    {
        $a = $this->sql_query->fetchAll();
        $this->sql_query->closeCursor();
        return $a;
    }
}