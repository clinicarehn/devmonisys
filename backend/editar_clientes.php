<?php	
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();
	
$clientes_id = $_POST['clientes_id'];

$tablaClientes = "clientes";
$camposClientes = ["clientes_id", "empresa", "rtn", "estado", "telefono", "date_create"];
$condicionesClientes_ = ["clientes_id" => $clientes_id];
$orderBy = "";
$resultadoClientes_ = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes_, $orderBy);

//Consultamos datos del usuario
$tabla = "usuarios";
$camposConsulta = ["has_expiration", "expiration_date"];
$condiciones = ["clientes_id" => $resultadoClientes_[0]['clientes_id']];
$orderBy = "";
$resultadoUsuarios = $database->consultarTabla($tabla, $camposConsulta, $condiciones, $orderBy);

if (!empty($resultadoUsuarios)) {
	$has_expiration = $resultadoUsuarios[0]['has_expiration'] == 1 ? 'Sí' : 'No';
	$expiration_date = $resultadoUsuarios[0]['expiration_date'] === NULL ? date('Y-m-d') : $resultadoUsuarios[0]['expiration_date'];
}

$datos = array(
	0 => $resultadoClientes_[0]['empresa'],
	1 => $resultadoClientes_[0]['clientes_id'],
	2 => $resultadoClientes_[0]['rtn'],
	3 => $resultadoClientes_[0]['estado'],
	4 => $has_expiration,
	5 => $expiration_date,
	6 => $resultadoClientes_[0]['telefono']
);
echo json_encode($datos);
?>	