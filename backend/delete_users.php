<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$usuario_login = $_SESSION['user_id'];

$usuarios_id = $_POST['usuarios_id'];
$nombre = $_POST['nombre'];

//ANTES DE ELIMINAR VALIDAMOS SI EL GRUPO ESTA RESGISTRADO EN EL HOSTS
$tablaUsers = "usuarios";
$condiciones_eliminar = ["usuarios_id" => $usuarios_id];

if($usuarios_id === $usuario_login){
    echo "error: Error no se puede eliminar el usuario $nombre";
}else{
    // Intentar eliminar los registros y devolver la respuesta
    if ($database->eliminarRegistros($tablaUsers, $condiciones_eliminar)) {
        echo "success"; // Envía 'success' si la eliminación fue exitosa
    } else {
        echo "error: Error no se puede eliminar el usuario $nombre";
    }
}
?>