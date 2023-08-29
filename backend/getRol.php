<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$usuario_rol = $_SESSION['rol'];

$tablaTipos = "rols";
$camposTipos = ["rols_id", "nombre"];
$condicionesTipos = [];
$orderBy = "";
$resultadoTipos = $database->consultarTabla($tablaTipos, $camposTipos, $condicionesTipos, $orderBy);

if (!empty($resultadoTipos)) {
    // Generar las opciones del select
    $options = '';
    foreach ($resultadoTipos as $row) {
        $rols_id = $row['rols_id'];
        $nombre = $row['nombre'];
        
        if($usuario_rol !== "superadmin"){
            // Verificar si el rol es "superadmin" y si no, omitirlo
            if ($nombre === "superadmin") {
                continue;
            }
        }

        if($usuario_rol === "reseller" || $usuario_rol === "admin"){
            // Verificar si el rol es "superadmin" y si no, omitirlo
            if ($nombre === "superadmin") {
                continue;
            }

            if ($nombre === "reseller") {
                continue;
            }            
        }
        
        // Agregar la opción al string de opciones
        $options .= "<option value='$rols_id'>$nombre</option>";
    }

    echo $options;
} else {
    echo '<option value="">No hay registros</option>';
}
?>