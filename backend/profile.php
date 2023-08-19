<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$empresa = $_POST["empresa"];
$rtn = $_POST["rtn"];

if (isset($_POST['submitType'])) {
    $submitType = $_POST['submitType'];
    
    if ($submitType === "modificar") {//Edamos los valores
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
            $datos_actualizar = ['empresa' => $empresa, 'image' => $imageFilename];
            $condiciones_actualizar = ["clientes_id" => $clientes_id];
        
            // Llamar a la función para actualizar los registros
            if ($database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar)) {
                echo "success";
            } else {
                echo "error: Error al modificar el cliente $empresa con el rtn $rtn";
            } 
        }else{
            $datos_actualizar = ['empresa' => $empresa, 'rtn' => $rtn, 'image' => $imageFilename];
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
    }
}
?>