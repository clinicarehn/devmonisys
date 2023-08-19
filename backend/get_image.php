<?php
    require_once "../backend/Database.php";

    $database = new Database();
    session_start();
    $clientes_id = $_SESSION['clientes_id'];

    $tablaClientes = "clientes";
    $camposClientes = ["image"];
    $condicionesClientes = ["clientes_id" => $clientes_id];
    $orderBy = "";
    $resultadoClientes = $database->consultarTabla($tablaClientes, $camposClientes, $condicionesClientes, $orderBy);

    if (!empty($resultadoClientes)) {
        $image = $resultadoClientes[0]['image'];
    }    

    $imageVersion = time(); // Generar un valor de tiempo único
    $imageNombre = isset($image) ? $image."?v=$imageVersion" : "logo.png?v=$imageVersion";
    

    // Devolver la URL completa de la imagen
    $imageUrl = "../img/logos/" . $imageNombre;
    echo $imageUrl;
?>