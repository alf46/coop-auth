<?php
$db = new mysql;
$conexion = $db->Connect();
if (!$conexion) {
    echo json_encode(
        array(
            'result' => 'error conectandose a la base de datos',
            'code' => 5
        )
    );
    exit();
}