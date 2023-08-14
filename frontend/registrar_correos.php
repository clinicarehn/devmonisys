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
                <div class="form-row">   
                    <div class="col-md-12 mb-3">
                        <label for="correo" class="form-label">Correo <span class="priority">*<span/></label>
                        <div class="input-group mb-3">
                        <input type="email" class="form-control" id="email" name="email" required data-placement="top" data-toggle="tooltip" data-placement="top" title="Correo">
                            <div class="input-group-append">				
                            <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fas fa-envelope-square"></i></span>
                            </div>
                        </div>
                    </div>                                            														
                </div>

                <div class="form-row">   
                    <div class="col-md-12 mb-3">
                        <div id="result"></div>
                    </div>                                          														
                </div>              

            <button type="submit" class="btn btn-primary" id="btnRegistro">Registrar</button>
            </form>
        </div>

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