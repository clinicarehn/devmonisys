<?php
  require_once "../backend/configGenerales.php";
?>

<nav class="navbar navbar-expand-lg navbar-light custom-header">
    <a class="navbar-brand" href="#">
        <img src="../img/logo_header.png" alt="CLINICARE" class="logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <!-- Menú dinámico se agregará aquí usando el script -->
        </ul>

        <!-- Enlace "Cerrar Sesión" visible en pantallas más grandes -->
        <a href="#" id="cerrar-sesion" class="nav-item nav-link d-none d-lg-block">Cerrar Sesión</a>

        <!-- Enlace "Cerrar Sesión" visible en dispositivos móviles -->
        <a href="#" id="cerrar-sesion-mobile" class="nav-item nav-link d-block d-lg-none"
            style="color: white !important; display: block; margin-top: 10px; text-align: center;">Cerrar Sesión</a>
    </div>
</nav>

<?php
  if (SISTEMA_PRUEBA=="SI"){ //CAJA
?>
<span class="container-fluid prueba-sistema">SISTEMA DE PRUEBA</span>
<?php
  }