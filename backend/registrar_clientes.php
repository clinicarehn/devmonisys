<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$empresa = $_POST["empresa"];
$rtn = $_POST["rtn"];
$email = $_POST["email"];
$pass = $_POST["pass"];
$estado = $_POST["estado"];
$rols = $_POST["rols"];

$tabla = "clientes";
$campos = ["clientes_id", "empresa", "rtn", "estado", "date_create"];
$campoCorrelativo = "clientes_id";
$valores = [$database->obtenerCorrelativo($tabla, $campoCorrelativo), $empresa, $rtn, $estado, date("y-m-d h:m:s")]; // Los valores correspondientes

// Validamos si el cliente ya existe
$tablaClientes = "clientes";
$camposClientes = ["clientes_id"];
$condicionesClientes = ["rtn" => $rtn];
$resultadoClientes = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes);

if (empty($resultadoClientes)) {
    // Registramos el Cliente
    if ($database->insertarRegistro($tabla, $campos, $valores)) {
        // Verificar si el cliente ya está registrado
        $condicionesClientes_ = ["empresa" => $empresa];
        $resultadoClientes_ = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes_);

        if (!empty($resultadoClientes_)) {
            $clientes_id = $resultadoClientes_[0]['clientes_id'];

            // Hashear la contraseña antes de guardarla en la base de datos
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la tabla "usuarios"
            $tablaUsuarios = "usuarios";
            $camposUsuarios = ["usuarios_id", "clientes_id", "email", "pass", "rols_id", "estado", "date_create"];
            $campoCorrelativoUsuarios = "usuarios_id";
            $valoresUsuarios = [$database->obtenerCorrelativo($tablaUsuarios, $campoCorrelativoUsuarios), $clientes_id, $email, $hashedPass, $rols, $estado, date("y-m-d h:m:s")];

            // Insertar el nuevo usuario en la tabla "usuarios"
            $database->insertarRegistro($tablaUsuarios, $camposUsuarios, $valoresUsuarios);
        }
              
        // Cliente registrado correctamente
        echo "success";
    } else {
        echo "error: Error al registrar el cliente";
    }
} else {
    echo "error-existe: El cliente ya está registrado";
}
?>