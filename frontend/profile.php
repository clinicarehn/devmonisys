<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile :: CLINICARE</title>
	<?php include 'css.php'; ?>
</head>
<body>
    <!-- Llamamos el Header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="content-container">
            <form id="formClientes">
                <input type="hidden" class="form-control" id="clientes_id" name="clientes_id" placeholder="Cliente">
                <div class="form-row">                
                    <div class="col-md-4 mb-3">
                        <label for="empresa" class="form-label">Empresa <span class="priority">*<span/></label>
                        <div class="input-group mb-3">                            
                            <input type="text" class="form-control" id="empresa" name="empresa" required data-toggle="tooltip" data-placement="top" title="Nombre de la Empresa">
                            <div class="input-group-append">				
                                <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-solid fa-building"></i></span>
                            </div>
                        </div>
                    </div>                  
                    <div class="col-md-4 mb-3">
                        <label for="rtn" class="form-label">RTN <span class="priority">*<span/></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="rtn" name="rtn" required data-toggle="tooltip" data-placement="top" title="RTN de la Empresa">
                            <div class="input-group-append">				
                                <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-regular fa-id-card"></i></span>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-4 mb-3">
                        <label for="imagen" class="form-label">Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="imagen" name="imagen" accept=".png">
                            <label class="custom-file-label" for="imagen" id="imagenLabel">Seleccione una imágen en formato png</label>
                        </div>
                        <div class="invalid-feedback">Por favor, seleccione una imágen en formato png.</div>
                    </div>                                   														
                </div>                      

                <div class="form-row">   
                    <div class="col-md-12 mb-3">
                        <div id="result"></div>
                    </div>                                          														
                </div>  

                <button type="submit" class="btn btn-primary" id="btnRegistroSave" name="submitType" value="registrar">Registrar</button>
                <button type="submit" class="btn btn-primary" id="btnRegistroEdit" name="submitType" value="modificar" style="display: none">Modificar</button>
            </form>
        </div>
		
		<div class="content-container-fluid">
			<div class="card-body"> 
				<div class="table-responsive">
					<table id="dataTableClientes" class="table table-striped table-condensed table-hover" style="width:100%">
						<thead>
							<tr>
								<th>Empresa</th>
								<th>RTN</th>
								<th>Editar</th>												
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
	<script src="../asset/js/profile.js" crossorigin="anonymous"></script>

</body>
</html>