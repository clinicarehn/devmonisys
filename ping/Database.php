<?php

class Database
{
    private $host = 'localhost';
    private $usuario = 'clinicarehn_clinicare';
    private $contrasena = 'Clin1c@r32022#';
    private $base_datos = 'clinicarehn_monisys';
    private $conexion;

    public function __construct()
    {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->base_datos);
        if ($this->conexion->connect_error) {
            die('Error de conexión: ' . $this->conexion->connect_error);
        }
    }

    public function __destruct()
    {
        $this->conexion->close();
    }

    public function getHostsInfo()
    {
        $query = "SELECT h.hosts_id, h.host AS 'ip', h.port AS 'port', h.nombre AS 'host', c.empresa, c.clientes_id, t.nombre AS tipo, h.ubicacion, c.rtn, c.image, c.telefono
            FROM hosts AS h
            INNER JOIN clientes AS c
            ON h.clientes_id = c.clientes_id
            INNER JOIN tipos AS t
            ON h.tipos_id = t.tipos_id
            WHERE h.activo = 1";

        $result = $this->conexion->query($query);

        // Manejo de errores en la consulta
        if (!$result) {
            echo 'Error en la consulta: ' . $this->conexion->error;
            return array();  // Retorna un array vacío para indicar que no se obtuvieron resultados
        }

        $hosts = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hosts[] = array(
                    'id' => $row['hosts_id'],
                    'host' => $row['host'],
                    'ip' => $row['ip'],
                    'nombre' => $row['empresa'],
                    'port' => $row['port'],
                    'clientes_id' => $row['clientes_id'],
                    'tipo' => $row['tipo'],
                    'ubicacion' => $row['ubicacion'],
                    'rtn' => $row['rtn'],
                    'image' => $row['image'],
                    'telefono' => $row['telefono']
                );
            }
        }

        return $hosts;
    }

    public function obtenerIdTipoPorNombre($tipoNombre)
    {
        $tipoId = null;

        // Escapamos el valor del nombre para evitar SQL Injection
        $tipoNombre = $this->conexion->real_escape_string($tipoNombre);

        $query = "SELECT tipos_id FROM tipos WHERE nombre = '$tipoNombre' LIMIT 1";

        $result = $this->conexion->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tipoId = $row['tipos_id'];
        }

        return $tipoId;
    }

    public function actualizarEstadoHost($hostId, $clienteId, $host, $port, $estado, $tipo)
    {
        // Obtenemos el ID del tipo según su nombre
        $tipoId = $this->obtenerIdTipoPorNombre($tipo);

        // Si no se encuentra el tipo, no realizamos la actualización
        if ($tipoId === null) {
            echo "No se encontró el tipo '$tipo' en la tabla tipos. No se actualizará el estado para el host: $host<br>";
            return;
        }

        // Convertimos el estado a un valor numérico
        $estadoNum = $estado === 'up' ? 1 : 0;

        // Realizamos la actualización en la base de datos
        $query = "UPDATE hosts 
                SET estado = '$estadoNum'
                WHERE hosts_id = '$hostId'";

        if ($this->conexion->query($query) === TRUE) {
            if ($port === '') {
                $this->guardarEnLogs("Estado actualizado con éxito para el host: $host, con el estado $estado", $clienteId, $hostId);
                echo "Estado actualizado con éxito para el host: $host, con el estado $estado<br>";
            } else {
                $this->guardarEnLogs("Estado actualizado con éxito para el host: $host, con el puerto $port, con el estado $estado", $clienteId, $hostId);
                echo "Estado actualizado con éxito para el host: $host, con el puerto $port, con el estado $estado<br>";
            }
        } else {
            if ($port === '') {
                $this->guardarEnLogs("Error al actualizar el estado para el host: $host, con el estado $estado. Error: " . $this->conexion->error, $clienteId, $hostId);
                echo "Error al actualizar el estado para el host: $host. Error: " . $this->conexion->error . '<br>';
            } else {
                $this->guardarEnLogs("Error al actualizar el estado para el host: $host, con el puerto $port, con el estado $estado. Error: " . $this->conexion->error, $clienteId, $hostId);
                echo "Error al actualizar el estado para el host: $host, con el puerto $port, con el estado $estado. Error: " . $this->conexion->error . '<br>';
            }
        }
    }

    // Función para obtener los hosts desde la tabla 'hosts'
    public function getHostsFromDatabase()
    {
        $sql = 'SELECT hosts FROM hosts';
        $result = $this->conexion->query($sql);

        $hosts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hosts[] = $row['hosts'];
            }
        }

        return $hosts;
    }

    // Función para obtener el nombre del cliente asociado a una IP desde la tabla 'hosts'
    public function getClienteFromHost($host)
    {
        $cliente = '';

        $sql = "SELECT clientes_id FROM hosts WHERE hosts = '$host'";
        $result = $this->conexion->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $clienteId = $row['clientes_id'];

            $sql = "SELECT nombre FROM clientes WHERE clientes_id = $clienteId";
            $result = $this->conexion->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $cliente = $row['nombre'];
            }
        }

        return $cliente;
    }

    // Función para obtener los destinatarios (direcciones de correo electrónico) asociados a un cliente desde la tabla 'clientes'
    public function getClientesDestinatariosFromDatabase($cliente)
    {
        $clientesDestinatarios = array();

        // Escapamos el valor del nombre del cliente para evitar SQL Injection
        $cliente = $this->conexion->real_escape_string($cliente);

        $query = "SELECT c.empresa, cc.email AS 'email'
            FROM clientes AS c
            INNER JOIN clientes_correo AS cc
            ON c.clientes_id = cc.clientes_id
            WHERE c.empresa = '$cliente'";
        $result = $this->conexion->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientesDestinatarios[$row['email']] = $cliente;
            }
        }

        return $clientesDestinatarios;
    }

    function guardarEnLogs($mensaje, $clienteId, $hosts_id)
    {
        $mensaje = $this->conexion->real_escape_string($mensaje);
        $date_create = date('y-m-d h:m:s');
        $query = "INSERT INTO logs (mensaje, clientes_id, fecha, hosts_id) VALUES ('$mensaje','$clienteId', '$date_create', '$hosts_id')";
        $this->conexion->query($query);
    }
}
?>