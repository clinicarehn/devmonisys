<?php
// Importamos la clase Database
require_once "Database.php";

require_once "Database.php";
require_once "sendEmail.php";

$database = new Database();
$sendEmail = new sendEmail();

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
                //OBTENER EL NOMBRE DEL ROLL
                $tablaRols = "rols";
                $camposRols = ["nombre"];
                $condicionesRols = ["rols_id" => $rols];
                $orderBy = "";
                $resultadoRols = $database->consultarTabla($tablaRols, $camposRols, $condicionesRols, $orderBy);
                $privilegio_nombre = "";

                if (!empty($resultadoRols)) {
                    $privilegio_nombre = $resultadoRols[0]['nombre'];
                }

                $urlSistema = "https://monitoring.clinicarehn.com/";
                $destinatarios = array($correo => $nombre);

                // Destinatarios en copia oculta (Bcc)
                $bccDestinatarios = [
                    'edwin.velasquez@clinicarehn.com' => 'CLINICARE',
                    'alexandra.ponce@clinicarehn.com' => 'CLINICARE'
                ];

                $asunto = "¡Bienvenido! Registro exitoso";
                $mensaje = '
                    <div padding: 20px;">
                        <p style="margin-bottom: 10px;">
                            ¡Hola '.$nombre.'!
                        </p>

                        <p style="margin-bottom: 10px;">
                            ¡Bienvenido a CLINICARE con Monitoring System!, tu herramienta de monitoreo confiable. Sabemos lo importante que es para ti tener tus equipos siempre disponibles, por eso estamos aquí para ayudarte.
                        </p>
                        
                        <p style="margin-bottom: 10px;">
                            Con nuestro sistema podrás validar de manera sencilla si tus equipos están activos o inactivos. Te proporcionamos la tranquilidad de estar al tanto de su estado en todo momento. No importa dónde estés, podrás acceder a la información que necesitas.
                        </p>

                        <p style="margin-bottom: 10px;">
                            Te damos las gracias por elegirnos como tu solución de confianza para el monitoreo de tus equipos y/o aplicaciones de manera eficiente. Tu registro en nuestro sistema ha sido exitoso y ahora eres parte de la familia CLINICARE.
                        </p>
                    
                        <ul style="margin-bottom: 12px;">
                            <li><b>Usuario</b>: '.$correo.'</li>
                            <li><b>Contraseña</b>: '.$contrasena.'</li>
                            <li><b>Perfil</b>: '.mb_convert_case(trim($privilegio_nombre), MB_CASE_TITLE, "UTF-8").'</li>
                            <li><b>Acceso al Sistema</b>: '.$urlSistema.'</li>
                        </ul>   
                        
                        <p style="margin-bottom: 10px;">
                            Recuerda que la seguridad es una prioridad para nosotros. Por ello, te recomendamos cambiar tu contraseña temporal en tu primera sesión.
                        </p>   
                        
                        <p style="margin-bottom: 10px;">
                            Si tienes alguna pregunta o necesitas ayuda en cualquier momento, no dudes en ponerte en contacto con nuestro dedicado equipo de soporte. Estamos aquí para proporcionarte la asistencia que necesitas.
                        </p>
                        
                        <p style="margin-bottom: 10px;">
                            ¡Empieza a explorar y a aprovechar al máximo nuestra plataforma de monitoreo! Tus equipos estarán en las mejores manos.
                        </p>

                        <p style="margin-bottom: 10px;">
                            Gracias por unirte a CLINICARE con Monitoring System. Esperamos que esta plataforma sea una herramienta valiosa para tu negocio.
                        </p>
                        
                        <p>
                            Saludos,
                        </p>
                        <p>
                            <b>CLINICARE</b>
                        </p>                
                    </div>
                ';
                $sendEmail->enviarCorreo($destinatarios, $bccDestinatarios, $asunto, $mensaje);

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