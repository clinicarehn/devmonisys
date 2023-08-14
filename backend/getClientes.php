<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

$tablaTipos = "clientes";
$camposTipos = ["clientes_id", "empresa"];
$condicionesTipos = [""];
$resultadoTipos = $database->consultarTabla($tablaTipos, $camposTipos, $condicionesTipos);

if (!empty($resultadoTipos)) {
	// Generar las opciones del select
	$options = '';
	foreach ($resultadoTipos as $row) {
		$clientes_id = $row['clientes_id'];
		$empresa = $row['empresa'];
		// Agregar la opción al string de opciones
		$options .= "<option value='$clientes_id'>$empresa</option>";
	}

	echo $options;
}else{
	echo '<option value="">No hay registros</option>';
}
?>