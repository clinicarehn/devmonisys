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
            <form id="formTipo">
            <div class="mb-3">
                <label for="grupo" class="form-label">Grupo</label>
                <input type="text" class="form-control" id="grupo" name="grupo" required data-placement="top" data-toggle="tooltip" data-placement="top" title="Aquí puedes agregar el grupo o el tipo de Host o Servicio">
            </div>

            <div id="result"></div>
            <button type="submit" class="btn btn-primary" id="btnRegistro">Registrar</button>
            </form>
        </div>
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>
	
	<?php include 'script.php'; ?>

</body>
</html>