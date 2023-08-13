<?php
// Incluye la clase Database
require_once "Database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$user_id = $_SESSION['user_id'];

// Consulta el rol del usuario en la tabla usuarios
$tabla = "usuarios";
$campos = ["rols_id"];
$condiciones = ["usuarios_id" => $user_id];
$resultados = $database->consultarTabla($tabla, $campos, $condiciones);

// Verifica si se obtuvieron resultados
if (!empty($resultados)) {
    $rol_id = $resultados[0]['rols_id'];
    // Consulta el nombre del rol en la tabla rols
    $tabla_roles = "rols";
    $campos_roles = ["nombre"];
    $condiciones_roles = ["rols_id" => $rol_id];
    $resultados_roles = $database->consultarTabla($tabla_roles, $campos_roles, $condiciones_roles);

    if (!empty($resultados_roles)) {
        $nombre_rol = $resultados_roles[0]['nombre'];
        echo $nombre_rol; // Devuelve el nombre del rol
    } else {
        echo "Rol no encontrado";
    }
} else {
    echo "Usuario no encontrado";
}
?>
