<?php
require_once "configGenerales.php";

require_once "Database.php";
require_once "sendEmail.php";

$database = new Database();
$sendEmail = new sendEmail();

// Inicia la sesión
session_start();

// Datos del nuevo usuario
$empresa = $_POST["empresa"];
$rtn = $_POST["rtn"];
$email = $_POST["email"];
$nombre_usuario = $_POST["nombre_usuario"];
$pass = $_POST["pass"];
$estado = $_POST["estado"];
$rols = $_POST["rols"];
$fecha_expiracion = $_POST["date_usuario"];
$validar = $_POST["validar"];//1. Si 2. No
$telefono = $_POST["telefono"];
$usuario_sistema = $_SESSION['user_id'];

if (isset($_POST['submitType'])) {
    $submitType = $_POST['submitType'];
    
    if ($submitType === "registrar") {//Registramos los valores
        $tabla = "clientes";
        $campos = ["clientes_id", "empresa", "telefono", "rtn", "image", "estado", "date_create", "usuarios_id"];
        $campoCorrelativo = "clientes_id";        
        
        // Validamos si el cliente ya existe
        $tablaClientes = "clientes";
        $camposClientes = ["clientes_id"];
        $condicionesClientes = ["rtn" => $rtn];
        $orderBy = "";
        $resultadoClientes = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes, $orderBy);
        
        if (empty($resultadoClientes)) {
            // Manejo del archivo subido

            $imageFilename = "";

            if (isset($_FILES["imagen"]["error"])) {
                if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                    // Obtener información del archivo subido
                    $imageFilename = $_FILES["imagen"]["name"];
                    $imageTmpPath = $_FILES["imagen"]["tmp_name"];
    
                    // Construir la ruta donde se guardará la imagen
                    $clientes_id = $database->obtenerCorrelativo($tabla, $campoCorrelativo);
                    $imageFilename = "logo_".$clientes_id.".png";
                    $imagePath = "../img/logos/".$imageFilename;
    
                    if (!file_exists($imagePath)) {
                        move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagePath);
                    }   
                }   
            }        

            $valores = [$database->obtenerCorrelativo($tabla, $campoCorrelativo), $empresa, $telefono, $rtn, $imageFilename, $estado, date("y-m-d h:m:s"), $usuario_sistema];

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
                $destinatarios = array($email => $empresa);

                // Destinatarios en copia oculta (Bcc)
                $bccDestinatarios = [
                    'edwin.velasquez@clinicarehn.com' => 'CLINICARE',
                    'alexandra.ponce@clinicarehn.com' => 'CLINICARE'
                ];

                $asunto = "¡Bienvenido! Registro exitoso";
                $mensaje = '
                    <div padding: 20px;">
                        <p style="margin-bottom: 10px;">
                            ¡Hola '.$empresa.'!
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
                            <li><b>Empresa</b>: '.$empresa.'</li>
                            <li><b>Usuario</b>: '.$email.'</li>
                            <li><b>Contraseña</b>: '.$pass.'</li>
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
                echo "error: Error al registrar el cliente $empresa con el rtn $rtn";
            }
        } else {
            echo "error-existe: El cliente $empresa con el rtn $rtn, ya está registrado";
        }

    } elseif ($submitType === "modificar") {//Edamos los valores
        $clientes_id = $_POST["clientes_id"];
        $imageFilename = "";

        if (isset($_FILES["imagen"]["error"])){
            if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                // Obtener información del archivo subido
                $imageFilename = $_FILES["imagen"]["name"];
                $imageTmpPath = $_FILES["imagen"]["tmp_name"];
    
                // Construir la ruta donde se guardará la imagen
                $imageFilename = "logo_".$clientes_id.".png";
                $imagePath = "../img/logos/".$imageFilename;
    
                if (file_exists($imagePath)) {
                    // Eliminar la imagen anterior si existe
                    unlink($imagePath);
                }

                if (!file_exists($imagePath)) {
                    move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagePath);
                }   
            }
        }

        //CONSULTAMOS EL RTN DEL CLIENTE
        $tabla = "clientes";
        $camposClientes = ["clientes_id", "rtn", "image"];
        $condicionesClientes_ = ["clientes_id" => $clientes_id];
        $orderBy = "";
        $resultadoClientes_ = $database->consultarTabla($tabla, $camposClientes, $condicionesClientes_, $orderBy);
        $rtn_consulta = $resultadoClientes_[0]['rtn'];

        if($imageFilename === "") {
            $imageFilename = $resultadoClientes_[0]['image'];
        }

        if($rtn === $rtn_consulta){
            $datos_actualizar = ['empresa' => $empresa, 'estado' => $estado, 'image' => $imageFilename];
            $condiciones_actualizar = ["clientes_id" => $clientes_id];
        
            // Llamar a la función para actualizar los registros
            if ($database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar)) {
                echo "success";
            } else {
                echo "error: Error al modificar el cliente $empresa con el rtn $rtn";
            } 
        }else{
            $datos_actualizar = ['empresa' => $empresa, 'rtn' => $rtn, 'estado' => $estado, 'image' => $imageFilename, 'has_expiration' => $imageFilename];
            $condiciones_actualizar = ["clientes_id" => $clientes_id];

            //VALIDAMOS SI EL RTN NO EXISTE ANTES DE GUARDARLO
            $condicionesClientesNueva = ["rtn" => $rtn];
            $orderBy = "";
            $resultadoClientes = $database->consultarTabla($tabla, $camposClientes, $condicionesClientesNueva, $orderBy);
            
            if (empty($resultadoClientes)) {
                // Llamar a la función para actualizar los registros
                if ($database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar)) {
                    echo "success";
                } else {
                    echo "error: Error al modificar el cliente $empresa con el rtn $rtn";
                } 
            }else{
                echo "error-existe: lo sentimos este rtn $rtn ya esta registrado";
            }           
        }

        $tabla = "usuarios";
        $datos_actualizar = ['has_expiration' => $validar, 'expiration_date' => $fecha_expiracion];
        $condiciones_actualizar = ["clientes_id" => $clientes_id]; 
        $database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar);
    }
}
?>