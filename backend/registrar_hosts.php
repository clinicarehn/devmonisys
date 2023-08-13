<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$host = $_POST["host"];
$ip = $_POST["ip"];
$port = $_POST["port"];
$ubicacion = $_POST["ubicacion"];
$tipo = $_POST["tipo"];
$estado = $_POST["estado"];
$clientes_id = "1";

//Validamos si existe el host antes de guardarlo
$tablahosts = "hosts";
$camposHotsConsulta = ["hosts_id"];
$condicionesHosts = ["host" => $ip, "port" => $port];
$resultadoHostsValidar = $database->consultarTabla($tablahosts, $camposHotsConsulta, $condicionesHosts);

if (empty($resultadoHostsValidar)) {
    // Registramos el Host
    $campoCorrelativo = "hosts_id";
    $camposHots = ["hosts_id", "clientes_id", "host", "port", "nombre", "ubicacion", "estado", "tipos_id", "date_create"];
    $valores = [$database->obtenerCorrelativo($tablahosts, $campoCorrelativo), $clientes_id, $ip, $port, $host, $ubicacion, 1, $tipo, date("y-m-d h:m:s")]; // Los valores correspondientes

    if ($database->insertarRegistro($tablahosts, $camposHots, $valores)) {
        // Cliente registrado correctamente
        echo "success";
    } else {
        echo "error: Error al registrar el la host";
    }
} else {
    echo "error-existe: El host ya está registrado";
}
?>