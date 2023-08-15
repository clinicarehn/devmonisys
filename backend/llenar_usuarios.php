<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();

$arreglo = array();
$data = array();

//Validamos si existe el host antes de guardarlo
$tabla = "usuarios";
$camposConsulta = ["usuarios_id", "clientes_id", "nombre", "email", "rols_id", "estado"];
$condicionesCorreos = ["estado" => "1"]; // Agregamos la condición del puerto
$resultadoCorreoValidar = $database->consultarTabla($tabla, $camposConsulta, $condicionesCorreos);

if (!empty($resultadoCorreoValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoCorreoValidar as $row) {
        $tablaClientes = "clientes";
        $camposConsultaClientes = ["empresa"];
        $condicionesClientes = ["clientes_id" => $row['clientes_id']]; // Agregamos la condición del puerto
        $resultadoClientesValidar = $database->consultarTabla($tablaClientes, $camposConsultaClientes, $condicionesClientes);
        $empresa_consulta = $resultadoClientesValidar[0]['empresa'];

        $tablaRols = "rols";
        $camposConsultaRols = ["nombre"];
        $condicionesRols = ["rols_id" => $row['rols_id']]; // Agregamos la condición del puerto
        $resultadoRolsValidar = $database->consultarTabla($tablaRols, $camposConsultaRols, $condicionesRols);
        $rol_consulta = $resultadoRolsValidar[0]['nombre'];        

        $data[] = array(
            "usuarios_id" => $row['usuarios_id'],
            "clientes_id" => $row['clientes_id'],
            "empresa" => $empresa_consulta,
            "nombre" => $row['nombre'],
            "email" => $row['email'],
            "rols_id" => $row['rols_id'],
            "rol" => $rol_consulta,
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