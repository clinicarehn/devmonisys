<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión u otra página
header("Location: ../index.php"); // Cambia "iniciar_sesion.php" por la página que quieras

exit(); // Asegúrate de detener la ejecución del script
?>