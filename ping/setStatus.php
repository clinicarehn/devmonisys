<?php
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

// Función para enviar ping y enviar correos electrónicos
function enviarPingSistema($hostsInfo, $intentosMaximos, $esperaEntreIntentos) {
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

enviarPingSistema($database->getHostsInfo(), 3, 1);
?>