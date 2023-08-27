<?php
require_once "configGenerales.php";

require_once "Database.php";
require_once "sendEmail.php";

$database = new Database();
$sendEmail = new sendEmail();

// Datos del nuevo usuario
$empresa = $_POST["empresa"];
$nombre_usuario = $_POST["usuario"];
$telefono = $_POST["telefono"];
$rtn = $_POST["rtn"];
$email = $_POST["email"];
$pass = $_POST["pass"];
$estado = 1;
$rols = 2;//Usuario administrador
$currentDate = date('Y-m-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
$newDate = date('Y-m-d', strtotime($currentDate . ' +7 days')); // Sumar 7 días a la fecha actual
$fecha_expiracion = $newDate;
$validar = 1;//1. Si 2. No

$tabla = "clientes";
$campos = ["clientes_id", "empresa", "telefono", "rtn", "estado", "date_create"];
$campoCorrelativo = "clientes_id";        

// Validamos si el cliente ya existe
$tablaClientes = "clientes";
$camposClientes = ["clientes_id"];
$condicionesClientes = ["rtn" => $rtn];
$orderBy = "";
$resultadoClientes = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes, $orderBy);

if (empty($resultadoClientes)) {
    $valores = [$database->obtenerCorrelativo($tabla, $campoCorrelativo), $empresa, $telefono, $rtn, $estado, date("y-m-d h:m:s")]; // Los valores correspondientes

    // Registramos el Cliente
    if ($database->insertarRegistro($tabla, $campos, $valores)) {
        // Verificar si el cliente ya está registrado
        $condicionesClientes_ = ["empresa" => $empresa];
        $orderBy = "";
        $resultadoClientes_ = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes_, $orderBy);

        if (!empty($resultadoClientes_)) {
            
            $clientes_id = $resultadoClientes_[0]['clientes_id'];

            // Hashear la contraseña antes de guardarla en la base de datos
            $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la tabla "usuarios"
            $tablaUsuarios = "usuarios";
            $camposUsuarios = ["usuarios_id", "clientes_id", "nombre", "email", "pass", "rols_id", "has_expiration",  "expiration_date", "estado", "date_create"];
            $campoCorrelativoUsuarios = "usuarios_id";
            $valoresUsuarios = [$database->obtenerCorrelativo($tablaUsuarios, $campoCorrelativoUsuarios), $clientes_id, $nombre_usuario, $email, $hashedPass, $rols, $validar, $fecha_expiracion, $estado, date("y-m-d h:m:s")];

            //VALIDAMOS SI EL CORREO NO EXISTE                    
            $tablaUsuariosValidar = "usuarios";
            $camposUsuariosValidar = ["usuarios_id"];
            $condicioneUsuariosValidar = ["email" => $email];
            $orderBy = "";
            $resultadoUsuariosValidar = $database->consultarTabla($tablaUsuariosValidar, $camposUsuariosValidar, $condicioneUsuariosValidar, $orderBy);                    

            if (empty($resultadoUsuariosValidar)) {
                // Insertar el nuevo usuario en la tabla "usuarios"
                $database->insertarRegistro($tablaUsuarios, $camposUsuarios, $valoresUsuarios);
            }
        }        

        $destinatarios = array($email => $empresa);

        $asunto = "¡Bienvenido! Registro exitoso";
        $mensaje = '
            <div padding: 20px;">
                <p style="margin-bottom: 10px;">
                    ¡Hola '.$empresa.'!
                </p>
        
                <p style="margin-bottom: 10px;">
                    Es un placer darte la bienvenida a <b>CLINICARE, Monitoring System</b>, tu herramienta de monitoreo confiable. Sabemos lo importante que es para ti tener tus equipos siempre disponibles, por eso estamos aquí para ayudarte.
                </p>
                
                <p style="margin-bottom: 10px;">
                    Con nuestro sistema podrás validar de manera sencilla si tus equipos están activos o inactivos. Te proporcionamos la tranquilidad de estar al tanto de su estado en todo momento. No importa dónde estés, podrás acceder a la información que necesitas.
                </p>
                
                <p style="margin-bottom: 12px;">
                    Si tienes alguna pregunta sobre cómo utilizar nuestra plataforma o cómo interpretar los datos de monitoreo, no dudes en contactarnos. Estamos comprometidos a brindarte el mejor soporte para que aproveches al máximo el sistema de monitoreo.
                </p>
                
                <p style="margin-bottom: 10px;">
                    ¡Empieza a explorar y a aprovechar al máximo nuestra plataforma de monitoreo! Tus equipos estarán en las mejores manos.
                </p>
                
                <p>
                    Saludos,
                </p>
                <p>
                    <b>CLINICARE</b>
                </p>                
            </div>
        ';
        $sendEmail->enviarCorreo($destinatarios, $asunto, $mensaje);

        //Cliente registrado correctamente
        echo "success";
    } else {
        echo "error: Error al registrar el cliente $empresa con el RTN $rtn";
    }
} else {
    echo "error-existe: El cliente $empresa con el RTN $rtn, ya está registrado, por favor inicie la sesión o restablezca su contraseña";
}
?>