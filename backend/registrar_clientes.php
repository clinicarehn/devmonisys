<?php
require_once "configGenerales.php";

// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

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

if (isset($_POST['submitType'])) {
    $submitType = $_POST['submitType'];
    
    if ($submitType === "registrar") {//Registramos los valores
        $tabla = "clientes";
        $campos = ["clientes_id", "empresa", "telefono", "rtn", "image", "estado", "date_create"];
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

            $valores = [$database->obtenerCorrelativo($tabla, $campoCorrelativo), $empresa, $telefono, $rtn, $imageFilename, $estado, date("y-m-d h:m:s")];

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