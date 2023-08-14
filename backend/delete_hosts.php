<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$hosts_id = $_POST['hosts_id'];
$host = $_POST['host'];

$tabla = "hosts";
$condiciones_eliminar = ["hosts_id" => $hosts_id];

// Intentar eliminar los registros y devolver la respuesta
if ($database->eliminarRegistros($tabla, $condiciones_eliminar)) {
    echo "success"; // Envía 'success' si la eliminación fue exitosa
} else {
    echo "error: Error no se puede eliminar este Host $host";
}
?>