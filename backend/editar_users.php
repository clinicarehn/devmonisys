<?php	
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();
	
$usuarios_id = $_POST['usuarios_id'];

$tablaUsers = "usuarios";
$camposUsers = ["usuarios_id", "clientes_id", "nombre", "email", "rols_id", "estado"];
$condicionesUsers = ["usuarios_id" => $usuarios_id];
$orderBy = "";
$resultadoUsers = $database->consultarTabla($tablaUsers, $camposUsers, $condicionesUsers, $orderBy);

$datos = array(
	0 => $resultadoUsers[0]['usuarios_id'],
	1 => $resultadoUsers[0]['clientes_id'],
	2 => $resultadoUsers[0]['nombre'],
	3 => $resultadoUsers[0]['email'],
	4 => $resultadoUsers[0]['rols_id'],
	5 => $resultadoUsers[0]['estado']	
);
echo json_encode($datos);
?>	