<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$email = $_POST["email"];

session_start();
$clientes_id = $_SESSION['clientes_id']; // Reemplaza esto con el ID de usuario autenticado

//Validamos si existe el host antes de guardarlo
$tabla = "clientes_correo";
$camposConsulta = ["clientes_id"];
$condiciones = ["email" => $email, "clientes_id" => $clientes_id]; // Agregamos la condición
$resultadoHostsValidar = $database->consultarTabla($tabla, $camposConsulta, $condiciones);

if (empty($resultadoHostsValidar)) {
    // Registramos el Host
    $campoCorrelativo = "clientes_correo_id";
    $campos = ["clientes_correo_id", "clientes_id", "email", "date_create"];
    $valores = [$database->obtenerCorrelativo($tabla, $campoCorrelativo), $clientes_id, $email, date("y-m-d h:m:s")]; // Los valores correspondientes

    if ($database->insertarRegistro($tabla, $campos, $valores)) {
        // Cliente registrado correctamente
        echo "success";
    } else {
        echo "error: Error al registrar el el correo $email";
    }
} else {
    echo "error-existe: El correo $email, ya está registrado";
}
?>