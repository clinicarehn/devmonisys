<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$clientes_id = $_SESSION['clientes_id'];
$rol_usuario = $_SESSION['rol'];
$usuario_sistema = $_SESSION['user_id'];

$arreglo = array();
$data = array();

//Validamos si existe el host antes de guardarlo
$tabla = "clientes";
$camposConsulta = ["clientes_id", "empresa", "rtn", "telefono", "estado"];

$condicionesCorreos = ["estado" => "1"];

if($rol_usuario === "reseller"){
    $condicionesCorreos = ["usuarios_id" => $usuario_sistema];
}

$orderBy = "";
$resultadoCorreoValidar = $database->consultarTabla($tabla, $camposConsulta, $condicionesCorreos, $orderBy);

if (!empty($resultadoCorreoValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoCorreoValidar as $row) {
        //Consultamos datos del usuario
        $tabla = "usuarios";
        $camposConsulta = ["has_expiration", "expiration_date"];
        $condiciones = ["clientes_id" => $row['clientes_id']];
        $orderBy = "";
        $resultadoUsuarios = $database->consultarTabla($tabla, $camposConsulta, $condiciones, $orderBy);

        if (!empty($resultadoUsuarios)) {
            $has_expiration = $resultadoUsuarios[0]['has_expiration'] == 1 ? 'Sí' : 'No';
            $expiration_date = $resultadoUsuarios[0]['has_expiration'] === NULL ? '' : $resultadoUsuarios[0]['expiration_date'];
        }

        $data[] = array(
            "clientes_id" => $row['clientes_id'],
            "empresa" => $row['empresa'],
            "rtn" => $row['rtn'],
            "estado" => $row['estado'],
            "has_expiration" => $has_expiration,
            "expiration_date" => $expiration_date,
            "telefono" => $row['telefono']
        );
    }
} 

$arreglo = array(
    "echo" => 1,
    "totalrecords" => count($data),
    "totaldisplayrecords" => count($data),
    "data" => $data
);

echo json_encode($arreglo);
?>