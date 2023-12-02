<?php	
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();
	
$hosts_id = $_POST['hosts_id'];

$tablaClientes = "hosts";
$camposClientes = ["hosts_id", "clientes_id", "host", "port", "nombre", "ubicacion", "estado", "tipos_id", 'activo'];
$condicionesClientes_ = ["hosts_id" => $hosts_id];
$orderBy = "";
$resultadoClientes_ = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes_, $orderBy);

$datos = array(
	0 => $resultadoClientes_[0]['hosts_id'],
	1 => $resultadoClientes_[0]['clientes_id'],
	2 => $resultadoClientes_[0]['host'],
	3 => $resultadoClientes_[0]['port'],
	4 => $resultadoClientes_[0]['nombre'],
	5 => $resultadoClientes_[0]['ubicacion'],
	6 => $resultadoClientes_[0]['estado'],
	7 => $resultadoClientes_[0]['tipos_id']	
	8 => $resultadoClientes_[0]['activo']	
);
echo json_encode($datos);
?>