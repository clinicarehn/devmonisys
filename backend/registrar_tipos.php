<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$grupo = strtoupper($_POST["grupo"]);

//Validamos si existe el host antes de guardarlo
$tablahosts = "tipos";
$camposHotsConsulta = ["nombre"];
$condicionesHosts = ["nombre" => $grupo]; // Agregamos la condición del puerto
$resultadoHostsValidar = $database->consultarTabla($tablahosts, $camposHotsConsulta, $condicionesHosts);

if (empty($resultadoHostsValidar)) {
    // Registramos el Host
    $campoCorrelativo = "tipos_id";
    $camposHots = ["tipos_id", "nombre", "date_create"];
    $valores = [$database->obtenerCorrelativo($tablahosts, $campoCorrelativo), $grupo, date("y-m-d h:m:s")]; // Los valores correspondientes

    if ($database->insertarRegistro($tablahosts, $camposHots, $valores)) {
        // Cliente registrado correctamente
        echo "success";
    } else {
        echo "error: Error al registrar el el grupo $grupo";
    }
} else {
    echo "error-existe: El grupo $grupo, ya está registrado";
}
?>