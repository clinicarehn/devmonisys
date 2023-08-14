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
                 <input type="hidden" class="form-control" id="hosts_id" name="hosts_id">
                <div class="form-row">   
                    <div class="col-md-6 mb-3">
                        <label for="host" class="form-label">Host <span class="priority">*<span/></label>
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" id="host" name="host" required data-placement="top" data-toggle="tooltip" data-placement="top" title="Aquí puedes agregar el nombre del Host o Servicio, y más detalles si se requiere">
                            <div class="input-group-append">				
                            <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-solid fa-server"></i></span>
                            </div>
                        </div>
                    </div>	                             
                    <div class="col-md-3 mb-3">
                        <label for="ip" class="form-label">IP <span class="priority">*<span/></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="ip" name="ip" required data-toggle="tooltip" data-placement="top" title="Agregar la IP Pública, no se puede registrar IP Privadas">
                            <div class="input-group-append">				
                                <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-solid fa-globe"></i></span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3 mb-3">
                    <label for="port" class="form-label">Port</label>	
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="port" name="port" data-toggle="tooltip" data-placement="top" title="Agregar el puerto si su aplicacion o servicio lo necesita">
                            <div class="input-group-append">				
                                <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-solid fa-ethernet"></i></span>
                            </div>
                        </div>
                    </div>                                														
                </div>
          
                <div class="form-row">   
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Ubicación <span class="priority">*<span/></label>
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required data-toggle="tooltip" data-placement="top" title="Ubicación">
                            <div class="input-group-append">				
                            <span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa-solid fa-location-dot"></i></span>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3 mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="priority">*<span/></label>			
                        <div class="input-group mb-3">
                            <select class="selectpicker" id="tipo" name="tipo" data-size="7" data-live-search="true" title="Tipo" required>			  
                            </select>
                        </div>
                    </div>                                             														
                </div>

                <div class="form-row">   
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado <span class="priority">*<span/></label>
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
                <table id="dataTableHosts" class="table table-striped table-condensed table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Host</th>
                            <th>IP</th>
                            <th>Port</th> 
                            <th>Grupo</th>                           
                            <th>Ubicación</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                </table>  
            </div>                   
        </div>
        <div class="card-footer small text-muted">

        </div>
    </div>   

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>

	<?php include 'script.php'; ?>
	<script src="../asset/js/hosts.js" crossorigin="anonymous"></script>

</body>
</html>