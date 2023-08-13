<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$email = $_POST['email'];
$clientes_id = $_POST['clientes_id'];

$tabla = "clientes_correo";
$condiciones_eliminar = ["email" => $email, "clientes_id" => $clientes_id];

// Intentar eliminar los registros y devolver la respuesta
if ($database->eliminarRegistros($tabla, $condiciones_eliminar)) {
    echo "success"; // Envía 'success' si la eliminación fue exitosa
} else {
    echo "error"; // Envía 'error' si hubo un error en la eliminación
}
?>