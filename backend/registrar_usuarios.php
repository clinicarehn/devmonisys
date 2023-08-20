<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();
$usuario_rol = $_SESSION['rol'];
$has_expiration = $_SESSION['has_expiration'];//1. Si 2. No
$expiration_date = $_SESSION['expiration_date'];

if($usuario_rol === "superadmin"){
    $clientes_id = $_POST["clientes_id"];
}else{
    $clientes_id = $_SESSION['clientes_id'];
}

// Datos del nuevo usuario
$correo = $_POST["correo"];
$contrasena = $_POST["contrasena"];
$rols = $_POST["rols"];
$nombre = $_POST["nombre"];
$estado = $_POST["estado"];

if (isset($_POST['submitType'])) {
    $submitType = $_POST['submitType'];
    
    if ($submitType === "registrar") {//Registramos los valores
        //Validamos si existe el Usuario antes de guardarlo
        $tablaUsers = "usuarios";
        $camposUsersConsulta = ["usuarios_id"];
        $condicionesUsers = ["email" => $correo];
        $orderBy = "";
        $resultadoUsersValidar = $database->consultarTabla($tablaUsers, $camposUsersConsulta, $condicionesUsers, $orderBy);

        if (empty($resultadoUsersValidar)) {
            // Registramos el Usuario
            $campoCorrelativo = "usuarios_id";
            $camposUsers = ["usuarios_id", "clientes_id", "nombre", "email", "pass", "rols_id", "estado", "date_create", "has_expiration", "expiration_date"];

            // Hashear la contraseña antes de guardarla en la base de datos
            $hashedPass = password_hash($contrasena, PASSWORD_DEFAULT);

            $valores = [$database->obtenerCorrelativo($tablaUsers, $campoCorrelativo), $clientes_id, $nombre, $correo, $hashedPass, $rols, $estado, date("y-m-d h:m:s"), $has_expiration, $expiration_date];

            if ($database->insertarRegistro($tablaUsers, $camposUsers, $valores)) {
                // Cliente registrado correctamente
                echo "success";
            } else {
                echo "error: Error al registrar el usuario";
            }
        } else {
            echo "error-existe: El usuario $correo ya está registrado";
        }

    } elseif ($submitType === "modificar") {//Modificamos los valores        
        $usuarios_id = $_POST["usuarios_id"];

        //Consultamos el correo del usuario
        $tablaUsers = "usuarios";
        $camposUsers = ["usuarios_id", "email"];
        $condicionesUsers_ = ["usuarios_id" => $usuarios_id];
        $orderBy = "";
        $resultadoUsers_ = $database->consultarTabla($tablaUsers, $camposUsers, $condicionesUsers_, $orderBy);
        $email_consulta = $resultadoUsers_[0]['email'];

        if($correo === $email_consulta){
            $datos_actualizar = ['nombre' => $nombre, 'rols_id' => $rols, 'estado' => $estado];
            $condiciones_actualizar = ["usuarios_id" => $usuarios_id];
        
            // Llamar a la función para actualizar los registros
            if ($database->actualizarRegistros($tablaUsers, $datos_actualizar, $condiciones_actualizar)) {
                echo "success";
            } else {
                echo "error: Error al modificar el usuario $nombre con el correo $correo";
            } 
        }else{
            $datos_actualizar = ['nombre' => $nombre, 'email' => $correo, 'rols_id' => $rols, 'estado' => $estado];
            $condiciones_actualizar = ["clientes_id" => $clientes_id];

            //Validamos si el correo no esta registrado
            $condicionesUsersNueva = ["email" => $correo];
            $orderBy = "";
            $resultadoUsers = $database->consultarTabla($tablaUsers, $camposUsers, $condicionesUsersNueva, $orderBy);

            if (empty($resultadoClientes)) {
                // Llamar a la función para actualizar los registros
                if ($database->actualizarRegistros($tablaUsers, $datos_actualizar, $condiciones_actualizar)) {
                    echo "success";
                } else {
                    echo "error: Error al modificar el cliente $nombre con el correo $correo";
                } 
            }else{
                echo "error-existe: lo sentimos este correo $correo ya esta registrado";
            }
        }
    }
}
?>