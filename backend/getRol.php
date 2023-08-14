<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$tablaTipos = "rols";
$camposTipos = ["rols_id", "nombre"];
$condicionesTipos = [];
$resultadoTipos = $database->consultarTabla($tablaTipos, $camposTipos, $condicionesTipos);

if (!empty($resultadoTipos)) {
    // Generar las opciones del select
    $options = '';
    foreach ($resultadoTipos as $row) {
        $rols_id = $row['rols_id'];
        $nombre = $row['nombre'];
        
        // Verificar si el rol es "superadmin" y si no, omitirlo
        if ($nombre === "superadmin") {
            continue;
        }
        
        // Agregar la opción al string de opciones
        $options .= "<option value='$rols_id'>$nombre</option>";
    }

    echo $options;
} else {
    echo '<option value="">No hay registros</option>';
}
?>