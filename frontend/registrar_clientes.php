<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registro Clientes :: CLINICARE</title>
	<?php include 'css.php'; ?>
</head>
<body>
    <!-- Llamamos el Header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="content-container">
            <form id="formClientes">
            <div class="mb-3">
                <label for="empresa" class="form-label">Empresa</label>
                <input type="text" class="form-control" id="empresa" name="empresa" required data-toggle="tooltip" data-placement="top" title="Nombre de la Empresa">
            </div>
            <div class="mb-3">
                <label for="rtn" class="form-label">RTN</label>
                <input type="text" class="form-control" id="rtn" name="rtn" required data-toggle="tooltip" data-placement="top" title="RTN de la Empresa">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo" required data-toggle="tooltip" data-placement="top" title="Correo de la Empresa, este se usara para iniciar sesión en el sistema">
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required data-toggle="tooltip" data-placement="top" title="La contraseña debe ser menor a 8 caracteres">
            </div>

            <div class="mb-3">
                <label for="rols" class="form-label">Rol</label>
                <select id="rols" name="rols" class="selectpicker" data-size="5" data-live-search="true" required data-toggle="tooltip" data-placement="top" title="Rol">
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
            <button type="submit" class="btn btn-primary" id="btnRegistro">Registrarse</button>
            </form>
        </div>
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>
	
	<?php include 'script.php'; ?>

</body>
</html>