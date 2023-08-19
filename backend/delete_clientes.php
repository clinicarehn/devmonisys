<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$clientes_id = $_POST['clientes_id'];
$empresa = $_POST['empresa'];

//ANTES DE ELIMINAR VALIDAMOS SI EL CLIENTE ESTA RESGISTRADO EN EL HOSTS
$tablaUsuarios = "usuarios";
$camposUsuarios = ["usuarios_id"];
$condicionesUsuarios = ["clientes_id" => $clientes_id];
$orderBy = "";
$resultadoUsuarios = $database->consultarTabla($tablaUsuarios, $camposUsuarios, $condicionesUsuarios, $orderBy);

if (empty($resultadoUsuarios)) {
    $tabla = "clientes";
    $condiciones_eliminar = ["clientes_id" => $clientes_id];
    
    // Intentar eliminar los registros y devolver la respuesta
    if ($database->eliminarRegistros($tabla, $condiciones_eliminar)) {
        echo "success"; // Envía 'success' si la eliminación fue exitosa
    } else {
        echo "error: Error al modificar el cliente"; // Envía 'error' si hubo un error en la eliminación
    }
}else{
    echo "error-existe: El cliente $empresa, cuenta con información, no se puede eliminar"; // Envía 'existe' si registro cuenta con información
}
?>