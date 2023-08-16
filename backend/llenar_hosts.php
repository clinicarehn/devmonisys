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
$tabla = "hosts";
$camposConsulta = ["hosts_id", "clientes_id", "host", "port", "nombre", "ubicacion", "estado", "tipos_id"];
$condicioneshosts = [];
$resultadoHostValidar = $database->consultarTabla($tabla, $camposConsulta, $condicioneshosts);

if (!empty($resultadoHostValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoHostValidar as $row) {
        //COSULTAMOS EL NOMBRE DEL CLIENTE
        $tablaClientes = "clientes";
        $camposConsultaClientes = ["empresa"];
        $condicionesClientes = ["clientes_id " => $row['clientes_id']];
        $resultadoClientes = $database->consultarTabla($tablaClientes, $camposConsultaClientes, $condicionesClientes);

        $cliente = "";
        if (!empty($resultadoClientes)) {
            $cliente = $resultadoClientes[0]['empresa'];
        }

        //COSULTAMOS EL NOMBRE DEL GRUPO
        $tablaGrupos = "tipos";
        $camposConsultaGrupos = ["nombre"];
        $condicionesGrupos = ["tipos_id" => $row['tipos_id']];
        $resultadoGrupos = $database->consultarTabla($tablaGrupos, $camposConsultaGrupos, $condicionesGrupos);

        $grupo = "";
        if (!empty($resultadoGrupos)) {
            $grupo = $resultadoGrupos[0]['nombre'];
        }        

        $data[] = array(
            "hosts_id" => $row['hosts_id'],
            "clientes_id" => $row['clientes_id'],
            "empresa" => $cliente,
            "host" => $row['host'],
            "port" => $row['port'],
            "nombre" => $row['nombre'],
            "ubicacion" => $row['ubicacion'],
            "estado" => $row['estado'],
            "tipos_id" => $row['tipos_id'],
            "grupo" => $grupo
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