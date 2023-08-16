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
$tabla = "logs";
$camposConsulta = ["id", "clientes_id", "hosts_id", "fecha", "mensaje"];
$condicioneshosts = ["clientes_id" => $clientes_id];
$resultadoHostValidar = $database->consultarTabla($tabla, $camposConsulta, $condicioneshosts);

if (!empty($resultadoHostValidar)) {
    // Llenar el array $data con los resultados
    foreach ($resultadoHostValidar as $row) {
        //Consultamos los datos del hosts
        $tablaHosts = "hosts";
        $camposConsultaHosts = ["nombre", "host", "port"];
        $condicionesHosts = ["hosts_id" => $row['hosts_id']];
        $resultadoHosts = $database->consultarTabla($tablaHosts, $camposConsultaHosts, $condicionesHosts);

        $nombre = "";
        $host = "";
        $port = "";

        if (!empty($resultadoHosts)) {
            $nombre = $resultadoHosts[0]['nombre'];
            $host = $resultadoHosts[0]['host'];
            $port = $resultadoHosts[0]['port'];
        }     

        //Consulamos el nombre de la empresa
        $tablaClientes = "clientes";
        $camposConsultaClientes = ["empresa"];
        $condicionesClientes = ["clientes_id" => $row['clientes_id']];
        $resultadoClientes = $database->consultarTabla($tablaClientes, $camposConsultaClientes, $condicionesClientes);

        $empresa = "";
     
        if (!empty($resultadoClientes)) {
            $empresa = $resultadoClientes[0]['empresa'];
        }       

        $data[] = array(
            "id" => $row['id'],
            "clientes_id" => $row['clientes_id'],
            "empresa" => $empresa,
            "nombre" => $nombre,
            "hosts_id" => $row['hosts_id'],
            "host" => $host,
            "port" => $port,
            "fecha" => $row['fecha'],
            "mensaje" => $row['mensaje'],
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