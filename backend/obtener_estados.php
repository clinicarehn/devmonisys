<?php
// Importamos la clase Database
require_once "Database.php";

// Creamos una instancia de la clase Database
$database = new Database();

// Obtenemos los estados de los hosts desde la base de datos
$hosts = $database->obtenerEstados();

// Agrupar los hosts por tipo
$hosts_por_tipo = array();
foreach ($hosts as $host) {
  $tipo = $host['tipo'];
  if (!isset($hosts_por_tipo[$tipo])) {
    $hosts_por_tipo[$tipo] = array();
  }
  $hosts_por_tipo[$tipo][] = $host;
}

echo json_encode($hosts_por_tipo);
?>