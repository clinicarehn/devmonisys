<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Historial :: CLINICARE</title>
	<?php include 'css.php'; ?>
</head>
<body>
    <!-- Llamamos el Header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="content-container">
            <div class="card-body"> 
                <div class="table-responsive">
                    <table id="dataTableHistorial" class="table table-striped table-condensed table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Empresa</th>
                                <th>Host</th>
                                <th>IP</th>
                                <th>Port</th> 
                                <th>Mensaje</th>
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
	<script src="../asset/js/historial.js" crossorigin="anonymous"></script>

</body>
</html>