<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes :: MoniSys</title>
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
                        <label for="empresa" class="form-label">Empresa <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="empresa" name="empresa" required
                                data-toggle="tooltip" data-placement="top" title="Nombre de la Empresa">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-building"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="rtn" class="form-label">RTN <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" id="rtn" name="rtn" required data-toggle="tooltip"
                                data-placement="top" title="RTN de la Empresa">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-regular fa-id-card"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="imagen" class="form-label">Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="imagen" name="imagen" accept=".png">
                            <label class="custom-file-label" for="imagen" id="imagenLabel">Seleccione una imágen en
                                formato png</label>
                        </div>
                        <div class="invalid-feedback">Por favor, seleccione una imágen en formato png.</div>
                    </div>
                </div>

                <div class="form-row visible">
                    <div class="col-md-4 mb-3" id="grupo-user">
                        <label for="nombre_usuario">Nombre Usuario <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required
                                data-toggle="tooltip" data-placement="top" title="Nombre del usuario">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3" id="grupo-user">
                        <label for="correo">Correo <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="correo" name="correo" required
                                data-toggle="tooltip" data-placement="top"
                                title="Correo de la Empresa, este se usara para iniciar sesión en el sistema">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fas fa-envelope-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="telefono">Teléfono <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-phone"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row visible""> 
                    <div class=" col-md-3 mb-3" id="grupo-user">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required
                            data-toggle="tooltip" data-placement="top"
                            title="La contraseña no debe ser menor a 8 caracteres y puede ser hasta 20 caracteres"
                            maxlength="20"
                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="sb-nav-link-icon"></div><i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3" id="grupo-user">
                    <label for="rols" class="form-label">Rol</label>
                    <div class="input-group mb-3">
                        <select class="selectpicker" id="rols" name="rols" required data-size="7"
                            data-live-search="true" title="Rol">
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3 visible">
                    <label for="date_usuario">Fecha Expiración <span class="priority">*<span /></label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" id="date_usuario" name="date_usuario"
                            value="<?php echo date("Y-m-d"); ?>" required data-toggle="tooltip" data-placement="top"
                            title="Fecha de Expiración">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="sb-nav-link-icon"></div><i class="fa-solid fa-calendar-days"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3 visible">
                    <label class="form-label" data-toggle="tooltip" data-placement="top"
                        title="Valiar Fecha de Expiración">Validar <span class="priority">*<span /></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="validar" id="validar_si" value="1">
                        <label class="form-check-label" for="activo">
                            Sí
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="validar" id="validar_no" value="0" checked>
                        <label class="form-check-label" for="inactivo">
                            No
                        </label>
                    </div>
                </div>
        </div>

        <div class="form-row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Estado <span class="priority">*<span /></label>
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

        <button type="submit" class="btn btn-primary" id="btnRegistroSave" name="submitType"
            value="registrar">Registrar</button>
        <button type="submit" class="btn btn-primary" id="btnRegistroEdit" name="submitType" value="modificar"
            style="display: none">Modificar</button>
        </form>
    </div>

    <div class="content-container-fluid">
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableClientes" class="table table-striped table-condensed table-hover"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>RTN</th>
                            <th>Teléfono</th>
                            <th>Validar</th>
                            <th>Fecha Expiración</th>
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
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>

    <?php include 'script.php'; ?>
    <script src="../asset/js/clientes.js" crossorigin="anonymous"></script>

</body>

</html>