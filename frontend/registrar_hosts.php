<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registro Hosts :: CLINICARE</title>
	<?php include 'css.php'; ?>
</head>
<body>
    <!-- Llamamos el Header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="content-container">
            <form id="formHosts">
            <div class="mb-3">
                <label for="host" class="form-label">Host</label>
                <input type="text" class="form-control" id="host" name="host" required data-placement="top" data-toggle="tooltip" data-placement="top" title="Aquí puedes agregar el nombre del Host o Servicio, y más detalles si se requiere">
            </div>
            <div class="mb-3">
                <label for="ip" class="form-label">IP</label>
                <input type="text" class="form-control" id="ip" name="ip" required data-toggle="tooltip" data-placement="top" title="Agregar la IP Pública, no se puede registrar IP Privadas">
            </div>
            <div class="mb-3">
                <label for="port" class="form-label">Port</label>
                <input type="text" class="form-control" id="port" name="port" data-toggle="tooltip" data-placement="top" title="Agregar el puerto si su aplicacion o servicio lo necesita">
            </div>            
            <div class="mb-3">
                <label for="correo" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required data-toggle="tooltip" data-placement="top" title="Ubicación">
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="tipo" class="selectpicker" data-size="5" data-live-search="true" required data-toggle="tooltip" data-placement="top" title="Tipo">
				</select>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado" id="activo" value="1" checked>
                    <label class="form-check-label" for="activo">
                        Activo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado" id="inactivo" value="0">
                    <label class="form-check-label" for="inactivo">
                        Inactivo
                    </label>
                </div>
            </div>            
            
            <div id="result"></div>
            <button type="submit" class="btn btn-primary" id="btnRegistro">Registrar</button>
            </form>
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>

	<?php include 'script.php'; ?>

</body>
</html>