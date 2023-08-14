<?php	
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();
	
$clientes_id = $_POST['clientes_id'];

$tablaClientes = "clientes";
$camposClientes = ["clientes_id", "empresa", "rtn", "estado", "date_create"];
$condicionesClientes_ = ["clientes_id" => $clientes_id];
$resultadoClientes_ = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes_);

$datos = array(
	0 => $resultadoClientes_[0]['empresa'],
	1 => $resultadoClientes_[0]['clientes_id'],
	2 => $resultadoClientes_[0]['rtn'],
	3 => $resultadoClientes_[0]['estado']
);
echo json_encode($datos);
?>	