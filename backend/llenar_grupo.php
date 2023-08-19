<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$clientes_id = $_SESSION['clientes_id'];

$arreglo = array();
$data = array();

//Validamos si existe el host antes de guardarlo
$tabla = "tipos";
$camposConsulta = ["tipos_id", "nombre"];
$condicionesCorreos = [];
$orderBy = "";
$resultadoCorreoValidar = $database->consultarTabla($tabla, $camposConsulta, $condicionesCorreos, $orderBy);

if (!empty($resultadoCorreoValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoCorreoValidar as $row) {
        $data[] = array(
            "tipos_id" => $row['tipos_id'],
            "nombre" => $row['nombre']
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