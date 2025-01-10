<?php

class Database
{
    private $host = 'localhost';
    private $usuario = 'esmultiservicios_root';
    private $contrasena = 'o8lXA0gtIO$@';
    private $base_datos = 'esmultiservicios_monisys';
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
        if ($this->conexion) {
            if (!$this->conexion->close()) {
                // Manejar el error al cerrar la conexión
                echo 'Error al cerrar la conexión: ' . $this->conexion->error;
            }
        }
    }

    public function obtenerEstados($clientes_id)
    {
        $query = "SELECT h.hosts_id, h.nombre, 
        CASE
            WHEN h.estado = 1 THEN 'up'    
            ELSE 'down'
        END AS 'estado', 
        t.nombre AS 'tipo' 
        FROM hosts AS h
        INNER JOIN tipos AS t
        ON h.tipos_id = t.tipos_id
        WHERE clientes_id = '$clientes_id' AND h.activo = 1";
        // AQUI HACEMOS EL WHERE PARA SOLO MOSTRAR LOS DATOS DEL CLIENTE QUE INICIO SESION

        $result = $this->conexion->query($query);
        $hosts = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hosts[] = array(
                    'id' => $row['hosts_id'],
                    'host' => $row['nombre'],
                    'nombre' => $row['nombre'],
                    'estado' => $row['estado'],
                    'tipo' => $row['tipo']
                );
            }
        }

        return $hosts;
    }

    public function obtenerCorrelativo($tabla, $campoCorrelativo)
    {
        $tabla = $this->conexion->real_escape_string($tabla);
        $campoCorrelativo = $this->conexion->real_escape_string($campoCorrelativo);

        $query = "SELECT MAX($campoCorrelativo) AS max_correlativo FROM $tabla";
        $result = $this->conexion->query($query);

        if ($result !== false && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $correlativo = (int) $row['max_correlativo'] + 1;
            return $correlativo;
        } else {
            // Si no se encuentra ningún registro, se asume que el correlativo empieza en 1
            return 1;
        }
    }

    public function consultarTabla($tabla, $campos = array(), $condiciones = array(), $orderBy = '')
    {
        $tabla = $this->conexion->real_escape_string($tabla);

        // Construir la consulta SELECT básica
        $query = 'SELECT ';

        if (empty($campos)) {
            $query .= '';  // Si no se especifican campos, seleccionar todos ()
        } else {
            $campos = array_map([$this->conexion, 'real_escape_string'], $campos);
            $query .= implode(',', $campos);
        }

        $query .= " FROM $tabla";

        // Si se especifican condiciones, agregarlas a la consulta
        if (!empty($condiciones)) {
            $clauses = array();
            foreach ($condiciones as $campo => $valor) {
                $campo = $this->conexion->real_escape_string($campo);
                $valor = $this->conexion->real_escape_string($valor);
                $clauses[] = "$campo = '$valor'";
            }
            $query .= ' WHERE ' . implode(' AND ', $clauses);  // Concatenar las condiciones usando AND
        }

        // Agregar cláusula ORDER BY si se especifica
        if (!empty($orderBy)) {
            $query .= " ORDER BY $orderBy";
        }

        // Ejecutar la consulta
        $result = $this->conexion->query($query);

        $resultados = array();

        if ($result) {  // Verificar si la consulta se ejecutó correctamente
            while ($row = $result->fetch_assoc()) {
                $resultados[] = $row;
            }
        } else {
            echo 'Error en la consulta: ' . $this->conexion->error;  // Mostrar mensaje de error
        }

        return $resultados;
    }

    public function insertarRegistro($tabla, $campos, $valores)
    {
        $tabla = $this->conexion->real_escape_string($tabla);

        // Escapar y formatear los campos para la consulta
        $campos = implode(',', array_map([$this->conexion, 'real_escape_string'], $campos));

        // Escapar y formatear los valores para la consulta
        $valores = "'" . implode("','", array_map([$this->conexion, 'real_escape_string'], $valores)) . "'";

        // Construir la consulta INSERT
        $query = "INSERT INTO $tabla ($campos) VALUES ($valores)";

        // Ejecutar la consulta
        if ($this->conexion->query($query) === TRUE) {
            return true;
        } else {
            // Si hay un error en la consulta, imprime el mensaje de error
            echo 'Error en la consulta: ' . $this->conexion->error;
            return false;
        }
    }

    public function actualizarRegistros($tabla, $datos, $condiciones = array())
    {
        $tabla = $this->conexion->real_escape_string($tabla);

        // Construir la consulta UPDATE
        $query = "UPDATE $tabla SET ";

        $actualizaciones = array();
        foreach ($datos as $campo => $valor) {
            $campo = $this->conexion->real_escape_string($campo);
            $valor = $this->conexion->real_escape_string($valor);
            $actualizaciones[] = "$campo = '$valor'";
        }
        $query .= implode(', ', $actualizaciones);

        // Si se especifican condiciones, agregarlas a la consulta
        if (!empty($condiciones)) {
            $clauses = array();
            foreach ($condiciones as $campo => $valor) {
                $campo = $this->conexion->real_escape_string($campo);
                $valor = $this->conexion->real_escape_string($valor);
                $clauses[] = "$campo = '$valor'";
            }
            $query .= ' WHERE ' . implode(' AND ', $clauses);  // Concatenar las condiciones usando AND
        }

        // Ejecutar la consulta
        if ($this->conexion->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * // Datos a actualizar
     * $datos_actualizar = array(
     *     'nombre' => 'Nuevo Nombre',
     *     'email' => 'nuevo_email@example.com',
     *     'activo' => 1
     * );
     *
     * // Condiciones para seleccionar los registros que se actualizarán
     * $condiciones_actualizar = array(
     *     'id = 1' // Actualizar el usuario con id = 1
     * );
     *
     * // Llamar a la función para actualizar los registros
     * if ($database->actualizarRegistros('usuarios', $datos_actualizar, $condiciones_actualizar)) {
     *     echo "Registros actualizados correctamente.";
     * } else {
     *     echo "Error al actualizar registros.";
     * }
     */

    public function eliminarRegistros($tabla, $condiciones = array())
    {
        $tabla = $this->conexion->real_escape_string($tabla);

        // Construir la consulta DELETE
        $query = "DELETE FROM $tabla";

        // Si se especifican condiciones, agregarlas a la consulta
        if (!empty($condiciones)) {
            $clauses = array();
            foreach ($condiciones as $campo => $valor) {
                $campo = $this->conexion->real_escape_string($campo);
                $valor = $this->conexion->real_escape_string($valor);
                $clauses[] = "$campo = '$valor'";
            }
            $query .= ' WHERE ' . implode(' AND ', $clauses);  // Concatenar las condiciones usando AND
        }

        // Ejecutar la consulta
        if ($this->conexion->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * // Condiciones para seleccionar los registros que se eliminarán
     * $condiciones_eliminar = array(
     *     'activo = 0' // Eliminar los usuarios inactivos
     * );
     *
     * // Llamar a la función para eliminar los registros
     * if ($database->eliminarRegistros('usuarios', $condiciones_eliminar)) {
     *     echo "Registros eliminados correctamente.";
     * } else {
     *     echo "Error al eliminar registros.";
     * }
     */
}

?>