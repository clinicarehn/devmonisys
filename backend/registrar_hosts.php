<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Datos del nuevo usuario
$host = strtoupper($_POST["host"]);
$ip = $_POST["ip"];
$port = $_POST["port"];
$ubicacion = $_POST["ubicacion"];
$tipo = $_POST["tipo"];
$estado = $_POST["estado"];
$clientes_id = "1";

if (isset($_POST['submitType'])) {
    $submitType = $_POST['submitType'];
    
    if ($submitType === "registrar") {//Registramos los valores
        //Validamos si existe el host antes de guardarlo
        $tablahosts = "hosts";
        $camposHotsConsulta = ["hosts_id"];
        $condicionesHosts = ["host" => $ip, "port" => $port];
        $resultadoHostsValidar = $database->consultarTabla($tablahosts, $camposHotsConsulta, $condicionesHosts);

        if (empty($resultadoHostsValidar)) {
            // Registramos el Host
            $campoCorrelativo = "hosts_id";
            $camposHots = ["hosts_id", "clientes_id", "host", "port", "nombre", "ubicacion", "estado", "tipos_id", "date_create"];
            $valores = [$database->obtenerCorrelativo($tablahosts, $campoCorrelativo), $clientes_id, $ip, $port, $host, $ubicacion, 1, $tipo, date("y-m-d h:m:s")]; // Los valores correspondientes

            if ($database->insertarRegistro($tablahosts, $camposHots, $valores)) {
                // Cliente registrado correctamente
                echo "success";
            } else {
                echo "error: Error al registrar el la host";
            }
        } else {
            echo "error-existe: El host ya está registrado";
        }

    } elseif ($submitType === "modificar") {//Modificamos los valores
        $hosts_id = $_POST["hosts_id"];

        //CONSULTAMOS EL RTN DEL CLIENTE
        $tabla = "hosts";
        $camposHosts = ["hosts_id", "host", "port"];
        $condicionesHosts_ = ["hosts_id" => $hosts_id];
        $resultadohosts_ = $database->consultarTabla($tabla, $camposHosts, $condicionesHosts_);
        $hosts_consulta = $resultadohosts_[0]['host'];
        $port_consulta = $resultadohosts_[0]['port'];

        if($ip === $hosts_consulta && $port  === $port_consulta){
            $datos_actualizar = ['nombre' => $host, 'ubicacion' => $ubicacion, 'estado' => $estado, 'tipos_id' => $tipo];
            $condiciones_actualizar = ["hosts_id" => $hosts_id];
        
            // Llamar a la función para actualizar los registros
            if ($database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar)) {
                echo "success";
            } else {
                echo "error: Error al modificar el hosts";
            } 
        }else{
            $camposHosts_ = ["hosts_id", "host", "port"];
            $datos_actualizar = ['nombre' => $host, 'ubicacion' => $ubicacion, 'estado' => $estado, 'host' => $ip, 'port' => $port, 'tipos_id' => $tipo];

            //Validamos si la IP y el port no existen antes de guardar
            $condicionesHostsNueva = ["host" => $ip, "port" => $port];
            $resultadoHosts = $database->consultarTabla($tabla, $camposHosts_, $condicionesHostsNueva);
            
            if (empty($resultadoHosts)) {
                // Llamar a la función para actualizar los registros
                if ($database->actualizarRegistros($tabla, $datos_actualizar, $condiciones_actualizar)) {
                    echo "success";
                } else {
                    echo "error: Error al modificar el hosts";
                } 
            }else{
                echo "error-existe: lo sentimos esta IP $ip con el Puerto $port ya estan registrado";
            }           
        }
    }
}
?>