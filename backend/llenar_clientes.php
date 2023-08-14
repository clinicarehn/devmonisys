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
$tabla = "clientes";
$camposConsulta = ["clientes_id ", "empresa", "rtn", "estado"];
$condicionesCorreos = []; // Agregamos la condición del puerto
$resultadoCorreoValidar = $database->consultarTabla($tabla, $camposConsulta, $condicionesCorreos);

if (!empty($resultadoCorreoValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoCorreoValidar as $row) {
        $data[] = array(
            "clientes_id" => $row['clientes_id'],
            "empresa" => $row['empresa'],
            "rtn" => $row['rtn'],
            "estado" => $row['estado']            
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