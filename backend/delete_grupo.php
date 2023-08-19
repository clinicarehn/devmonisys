<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$tipos_id = $_POST['tipos_id'];
$nombre = $_POST['nombre'];

//ANTES DE ELIMINAR VALIDAMOS SI EL GRUPO ESTA RESGISTRADO EN EL HOSTS
$tablaHosts = "hosts";
$camposHosts = ["hosts_id"];
$condicionesHosts = ["tipos_id" => $tipos_id];
$orderBy = "";
$resultadoHosts = $database->consultarTabla($tablaHosts, $camposHosts, $condicionesHosts, $orderBy);

if (empty($resultadoHosts)) {
    $tabla = "tipos";
    $condiciones_eliminar = ["tipos_id" => $tipos_id];
    
    // Intentar eliminar los registros y devolver la respuesta
    if ($database->eliminarRegistros($tabla, $condiciones_eliminar)) {
        echo "success"; // Envía 'success' si la eliminación fue exitosa
    } else {
        echo "error: Error no se puede eliminar este Grupo $nombre";
    }
}else{
    echo "error-existe: lo sentimos este grupo $nombre cuenta con información, no se puede eliminar";
}
?>