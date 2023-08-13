<?php
// Incluye la clase Database
require_once "Database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Inicia la sesión
session_start();

$response = array(); // Para almacenar el mensaje de respuesta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene los valores del formulario
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Obtiene los datos del usuario desde la base de datos
    $tabla = "usuarios";
    $campos = ["usuarios_id", "clientes_id", "email", "pass", "rols_id", "estado"];
    $condiciones = ["email" => $email];
    $usuarios = $database->consultarTabla($tabla, $campos , $condiciones);

    if (!empty($usuarios)) {
        $userData = $usuarios[0];

        // Verifica la contraseña
        if (password_verify($password, $userData['pass'])) {
            // Obtiene el rol del usuario desde la tabla de roles
            $condicionesRoles = ["rols_id" => $userData['rols_id']];
            $roles = $database->consultarTabla("rols", ["nombre"], $condicionesRoles);

            if (!empty($roles)) {
                $userRole = $roles[0]['nombre'];
            } else {
                // Si no se encuentra el rol, establece un valor predeterminado (puedes ajustarlo según tu lógica)
                $userRole = "user";
            }

            // Inicia sesión y asigna el rol del usuario
            $_SESSION['user_id'] = $userData['usuarios_id'];
            $_SESSION['rol'] = $userRole;
            $_SESSION['clientes_id'] = $userData['clientes_id'];

            // Agrega un mensaje de éxito al array de respuesta
            $response['message'] = 'success';
        } else {
            // Agrega un mensaje de error al array de respuesta
            $response['message'] = 'Credenciales inválidas. Inténtalo de nuevo.';
        }
    } else {
        // Agrega un mensaje de error al array de respuesta
        $response['message'] = 'Usuario no encontrado. Inténtalo de nuevo.';
    }
} else {
    // Agrega un mensaje de error al array de respuesta
    $response['message'] = 'Petición inválida.';
}

// Devuelve la respuesta como JSON
echo json_encode($response);
?>