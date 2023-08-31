<?php
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; 

class sendEmail {

    public function __construct() {

    }

    public function enviarCorreo($destinatarios, $bccDestinatarios, $asunto, $mensaje) {  
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

                // Agregar destinatarios en copia oculta (Bcc)
                foreach ($bccDestinatarios as $bccEmail => $bccNombre) {
                    $mail->addBCC($bccEmail, $bccNombre);
                }                
            
                // Asunto y cuerpo del correo con la plantilla HTML
                $mail->Subject = $asunto;

                // Cuerpo del mensaje utilizando la plantilla
                $htmlMensaje = $this->getCorreoPlantilla($asunto, $mensaje);

                $mail->Body = $htmlMensaje;
    
                // Envío del correo
                if (!$mail->send()) {                                    
                    echo "El correo no se puede enviar. Error: " . $mail->ErrorInfo;
                }
            
                // Limpiar los destinatarios y adjuntos para el siguiente correo
                $mail->clearAddresses();
                $mail->ClearAttachments();
            } 
        } catch (Exception $e) {
            
        }
    }

    public function getCorreoPlantilla($asunto, $mensaje) {
        // Datos de tu empresa
        $nombreEmpresa = 'CLINICARE';
        $direccionEmpresa = 'Col. Monte Carlo, 6-7 , 22 AVENIDA B Casa #17 San Pedro Sula, Cortés';
        $telefonoEmpresa = '+504 2503-5517';
        $sitioWebEmpresa = 'https://clinicarehn.com';
        $urlLogoEmpresa = 'https://fayad.clinicarehn.com/vistas/plantilla/img/logo.png';
    
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
    
        // Cuerpo del mensaje
        $htmlMensaje = '<html>
        <head>
        <title>'.$asunto.'</title>
        </head>
        <body>
        '.$encabezado.'
        <div style="padding: 20px;">
            <h1>'.$asunto.'</h1>
            '.$mensaje.'
        </div>
        '.$pieDePagina.'
        </body>
        </html>';
    
        return $htmlMensaje;
    }
}
?>