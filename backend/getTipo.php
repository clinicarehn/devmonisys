<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$tablaTipos = "tipos";
$camposTipos = ["tipos_id", "nombre"];
$condicionesTipos = [""];
$resultadoTipos = $database->consultarTabla($tablaTipos, $camposTipos, $condicionesTipos);

if (!empty($resultadoTipos)) {
	// Generar las opciones del select
	$options = '';
	foreach ($resultadoTipos as $row) {
		$tipoId = $row['tipos_id'];
		$nombre = $row['nombre'];
		// Agregar la opción al string de opciones
		$options .= "<option value='$tipoId'>$nombre</option>";
	}

	echo $options;
}else{
	echo '<option value="">No hay registros</option>';
}
?>