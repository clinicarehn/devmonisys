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
            <form id="formCorreos">
            <div class="mb-3">
                <label for="grupo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="email" name="email" required data-placement="top" data-toggle="tooltip" data-placement="top" title="Correo">
            </div>

            <div id="result"></div>
            <button type="submit" class="btn btn-primary" id="btnRegistro">Registrar</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="content-container-fluid">

            <div class="card-body"> 
                <div class="table-responsive">
                    <table id="dataTableCorreos" class="table table-striped table-condensed table-hover" style="width:100%">
                        <thead>
                        <tr>
                        <th>Correo</th>
                        <th>Eliminar</th>														
                        </tr>
                        </thead>
                    </table>  
                </div>                   
            </div>
            <div class="card-footer small text-muted">

            </div>
        </div>
    </div>	       
    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>

	<?php include 'script.php'; ?>
    <script src="../asset/js/correo.js" crossorigin="anonymous"></script>
	
</body>
</html>