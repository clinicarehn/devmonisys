<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

date_default_timezone_set("America/Tegucigalpa");

// Incluye la clase Database
require 'Database.php';

// Crea una instancia de la clase Database
$database = new Database();

function getOS() {
    $os = strtoupper(PHP_OS);

    if (substr($os, 0, 3) === 'WIN') {
        return 'WINDOWS';
    } elseif (strpos($os, 'DARWIN') !== false) {
        return 'MACOS';
    } elseif (strpos($os, 'LINUX') !== false) {
        return 'LINUX';
    } else {
        return 'UNKNOWN';
    }
}

function enviarPing($host) {
    ini_set('max_execution_time', 300); // Establece el tiempo máximo de ejecución a 300 segundos (5 minutos)
    $os = getOS();
    $command = '';

    if ($os === 'WINDOWS') {
        $command = "ping -n 1 -w 1000 $host";
    } elseif ($os === 'MACOS' || $os === 'LINUX') {
        $command = "ping -c 1 -W 1 $host";
    } else {
        echo "Sistema operativo no compatible: $os";
        return false;
    }

    exec($command, $output, $status);
    // Verificar el contenido de $output para determinar si el ping fue exitoso.
    return $status === 0 && !preg_grep('/(100% packet loss|unreachable)/i', $output);
}

function getCorreoPlantilla($asunto, $mensaje) {
    // Datos de tu empresa
    $nombreEmpresa = 'CLINICARE';
    $direccionEmpresa = 'Col. Monte Carlo, 6-7 , 22 AVENIDA B Casa #17 San Pedro Sula, Cortés';
    $telefonoEmpresa = '+504 2503-5517';
    $sitioWebEmpresa = 'https://clinicarehn.com';
    $urlLogoEmpresa = 'https://fayad.clinicarehn.com/vistas/plantilla/img/logo.png'; // Reemplaza con la URL de tu logo

    // Encabezado del correo
    $encabezado = '
        <div style="background-color: #f2f2f2; padding: 20px; text-align: center;">
            <img src="'.$urlLogoEmpresa.'" alt="Logo de '.$nombreEmpresa.'" style="max-width: 70%;">
            <h1>'.$nombreEmpresa.'</h1>
            <p>'.$direccionEmpresa.'</p>
            <p>Teléfono: '.$telefonoEmpresa.'</p>
            <p>Sitio Web: '.$sitioWebEmpresa.'</p>
        </div>';

    // Pie de página del correo
    $pieDePagina = '<div style="background-color: #f2f2f2; padding: 20px; text-align: center;">
        <p><b>Este correo fue enviado por '.$nombreEmpresa.', por favor no respondas a este correo</b>.</p>
    </div>';

    $mensajeCuerpo = '';
    if($mensaje['port'] === "") {
        $mensajeCuerpo = '
            <ul>
                <li>Host: <b>'.$mensaje['host'].'</b></li>
                <li>Dirección IP: <b>'.$mensaje['ip'].'</b></li>
                <li>Ubicación: <b>'.$mensaje['ubicacion'].'</b></li>
            </ul>        
        ';
    }else{
        $mensajeCuerpo = '
            <ul>
                <li>Host: <b>'.$mensaje['host'].'</b></li>
                <li>Dirección IP: <b>'.$mensaje['ip'].'</b></li>
                <li>Puerto: <b>'.$mensaje['port'].'</b></li>
                <li>Ubicación: <b>'.$mensaje['ubicacion'].'</b></li>
            </ul>        
        ';
    }

    // Cuerpo del mensaje
    $htmlMensaje = '<html>
    <head>
      <title>'.$asunto.'</title>
    </head>
    <body>
      '.$encabezado.'
      <div style="padding: 20px;">
        <h1>'.$asunto.'</h1>
        <p>Hola,</p>
        <p>Este es un mensaje automático para informarte que se han realizado varios intentos fallidos de ping al host, <strong>'.$mensaje['host'].'</strong>.</p>
        <p>Detalles del host:</p>
        '.$mensajeCuerpo.'
        <p>Por favor, revisa el servidor para solucionar cualquier problema que pueda estar afectando la conectividad.</p>
        <p>Si necesitas ayuda, no dudes en contactarnos.</p>
        <p>Saludos,</p>
        <p><b>'.$nombreEmpresa.'</b></p>
      </div>
      '.$pieDePagina.'
    </body>
    </html>';

    return $htmlMensaje;
}

function enviarCorreo($destinatarios, $asunto, $mensaje, $clienteId) {
    $database = new Database();

    ini_set('max_execution_time', 300); // Establece el tiempo máximo de ejecución a 300 segundos (5 minutos)
    $mail = new PHPMailer(true);
    $correo_empresa = 'clinicare@clinicarehn.com';
    $pass_empresa = 'Cl1nicare2021#';
    $de_empresa = 'CLINICARE';
    $smtp = 'smtp.office365.com';

    try {
        // Configuración del servidor de correo saliente (SMTP)
        $mail->isSMTP();
        $mail->SMTPKeepAlive = true;
        $mail->Host          = $smtp; // Cambiar por el servidor de correo saliente
        $mail->SMTPAuth      = true;
        $mail->Username      = $correo_empresa; // Cambiar por tu correo electrónico
        $mail->Password      = $pass_empresa; // Cambiar por tu contraseña de correo
        $mail->SMTPSecure    = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port          = 587;

        // Configuración del correo
        $mail->setFrom($correo_empresa, $de_empresa);
        $mail->isHTML(true);
        // Especificamos el conjunto de caracteres para el mensaje y los encabezados
        $mail->CharSet = 'UTF-8';

        foreach ($destinatarios as $email => $nombre) {
            $mail->addAddress($email, $nombre);
        
            // Asunto y cuerpo del correo con la plantilla HTML
            $mail->Subject = $asunto;

            // Cuerpo del mensaje utilizando la plantilla
            $htmlMensaje = getCorreoPlantilla($asunto, $mensaje);
            $mail->Body = $htmlMensaje;

            $host = $mensaje['host'];
            $ip = $mensaje['ip'];
            $port = $mensaje['port'];
            $cliente = $mensaje['cliente'];
            $hosts_id = $mensaje['hosts_id'];

            // Envío del correo
            if (!$mail->send()) {
                if($port === ""){
                    $database->guardarEnLogs("Error al enviar los datos del cliente ".$cliente.", host ".$host." con la IP asginada ".$ip." al correo " . $email . ': ' . $mail->ErrorInfo, $clienteId, $hosts_id);
                }else{
                    $database->guardarEnLogs("Error al enviar los datos del cliente ".$cliente.", host ".$host." con la IP asginada $ip con el puerto $port, al correo " . $email . ': ' . $mail->ErrorInfo, $clienteId, $hosts_id);
                }
                
            } else {
                if($port === ""){
                    $database->guardarEnLogs("Correo enviado exitosamente a $email, cliente $cliente, host $host con la IP asignada ".$ip.", debido a que su conexión presenta problemas", $clienteId, $hosts_id);
                }else{
                    $database->guardarEnLogs("Correo enviado exitosamente a $email, cliente $cliente, host $host con la IP asignada $ip con el puerto $port, debido a que su conexión presenta problemas", $clienteId, $hosts_id);
                }                
            }
        
            // Limpiar los destinatarios y adjuntos para el siguiente correo
            $mail->clearAddresses();
            $mail->ClearAttachments();
        } 
    } catch (Exception $e) {
        $database->guardarEnLogs("Error al enviar el correo: {$mail->ErrorInfo}", $clienteId, $hosts_id);
    }
}

// Función para enviar ping y enviar correos electrónicos
function enviarPingYEnviarCorreo($hostsInfo, $intentosMaximos, $esperaEntreIntentos) {
    $database = new Database();

    foreach ($hostsInfo as $hostInfo) {
        $ip = $hostInfo['ip'];
        $port = $hostInfo['port'];
        $host = $hostInfo['host'];        
        $ubicacion = $hostInfo['ubicacion'];
        $clienteId = $hostInfo['clientes_id'];
        $cliente = $hostInfo['nombre'];
        $hosts_id = $hostInfo['id'];

        $intentosFallidos = 0;

        if($port === ""){
            // Intenta enviar pings        
            while ($intentosFallidos < $intentosMaximos) {
                if (enviarPing($ip)) {
                    $database->guardarEnLogs("El ping a $ip correspondiente al host $host, para el cliente ".$cliente.", fue exitoso.", $clienteId, $hosts_id);

                    if (!empty($clienteId)) {
                        // Si el cliente existe en la base de datos, actualizamos el estado del host a 'up'
                        $database->actualizarEstadoHost($hostInfo['id'], $clienteId, $ip, $port, 'up', $hostInfo['tipo']);
                    } else {
                        $database->guardarEnLogs("No se encontró el cliente asociado a la IP $ip", $clienteId, $hosts_id);
                    }

                    break;
                } else {
                    $intentosFallidos++;
                    sleep($esperaEntreIntentos);
                }
            }
        }else{
            // Crear arrays para almacenar las IPs y puertos a verificar
            $ipsToCheck = [];
            $portsToCheck = [];

            // Iterar a través de la información de hosts y llenar los arrays
            foreach ($hostsInfo as $hostInfo) {
                $ipsToCheck[] = $hostInfo['ip'];
                $portsToCheck[] = $hostInfo['port'];
            }

            // Llamar a la función de verificación de servicios
            $results = checkServicesForIPs($ipsToCheck, $portsToCheck);

            while ($intentosFallidos < $intentosMaximos) {
                foreach ($results as $result) {
                    if ($result['status'] === 'Inactivo') {
                        $intentosFallidos++;
                        sleep($esperaEntreIntentos);
                    } else {
                        if (!empty($clienteId)) {
                            // Si el cliente existe en la base de datos, actualizamos el estado del host a 'up'
                            $database->actualizarEstadoHost($hostInfo['id'], $clienteId, $ip, $port, 'up', $hostInfo['tipo']);
                        } else {
                            $database->guardarEnLogs("No se encontró el cliente asociado a la IP $ip con el puerto $port", $clienteId, $hosts_id);
                        }
    
                        break;
                    }
                }  
            } 
        }

        // Si los intentos de ping han fallado, envía el correo electrónico de alerta
        if ($intentosFallidos === $intentosMaximos) {
            if (!empty($cliente)) {
                $destinatarios = $database->getClientesDestinatariosFromDatabase($cliente);
                $asunto = "Alerta: $intentosMaximos intentos fallidos de ping al host, $host";

                if($port === ""){
                    $mensaje = array(
                        'host' => $host,
                        'ip' => $ip,
                        'port' => $port,
                        'ubicacion' => $ubicacion,
                        'cliente' => $cliente,
                        'hosts_id' => $hosts_id
                    );
                }else{
                    $mensaje = array(
                        'host' => $host,
                        'ip' => $ip,
                        'port' => $port,
                        'ubicacion' => $ubicacion,
                        'cliente' => $cliente,
                        'hosts_id' => $hosts_id
                    );                    
                }

                enviarCorreo($destinatarios, $asunto, $mensaje, $clienteId);
                // Actualizamos el estado del host a 'down'
                $database->actualizarEstadoHost($hostInfo['id'], $clienteId, $ip, $port, 'down', $hostInfo['tipo']);
            }
        }
    }
}

function checkService($ip, $port, $timeout = 5) {
    $socket = @fsockopen($ip, $port, $errorNumber, $errorString, $timeout);

    if ($socket) {
        fclose($socket);
        return true; // El servicio está activo
    } else {
        return false; // El servicio está inactivo
    }
}

function checkServicesForIPs($ips, $ports) {
    $results = [];

    foreach ($ips as $index => $ip) {
        $port = $ports[$index];
        $serviceStatus = checkService($ip, $port);

        $results[] = [
            'ip' => $ip,
            'port' => $port,
            'status' => $serviceStatus ? 'Activo' : 'Inactivo'
        ];
    }

    return $results;
}

enviarPingYEnviarCorreo($database->getHostsInfo(), 3, 1);
?>