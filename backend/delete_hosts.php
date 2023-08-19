<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$hosts_id = $_POST['hosts_id'];
$host = $_POST['host'];

$tabla = "hosts";
$condiciones_eliminar = ["hosts_id" => $hosts_id];

//ANTES DE ELIMINAR VALIDAMOS SI EL GRUPO ESTA RESGISTRADO EN EL HOSTS
$tablaLogs = "logs";
$camposLogs = ["id"];
$condicionesLogs = ["hosts_id" => $hosts_id];
$orderBy = "";
$resultadoLogs = $database->consultarTabla($tablaLogs, $camposLogs, $condicionesLogs, $orderBy);

if (empty($resultadoLogs)) {
    // Intentar eliminar los registros y devolver la respuesta
    if ($database->eliminarRegistros($tabla, $condiciones_eliminar)) {
        echo "success"; // Envía 'success' si la eliminación fue exitosa
    } else {
        echo "error: Error no se puede eliminar este Host $host";
    }
}else{
    echo "error-existe: lo sentimos este host $host cuenta con información, no se puede eliminar, puede inactivarlo si desea";
}
?>