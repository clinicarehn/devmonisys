<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login :: MoniSys</title>
    <!-- Enlaces a Bootstrap, CSS personalizado y JS -->
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/css/stylelogin.css">
    <link rel="shortcut icon" href="img/icono.png">
</head>

<body>
    <div class="container-fluid">
        <div class="login-container">
            <!-- Logo de la empresa -->
            <div class="logo">
                <img src="img/logo.png" alt="CLINICARE" class="logo">
            </div>

            <!-- Formulario de recuperación de contraseña -->
            <form class="form" id="recuperarContrasenaForm" autocomplete="off" style="display: none">
                <!-- Título del formulario -->
                <h4 class="text-center mb-4">Restablecer Contraseña</h4>

                <div class="form-group">
                    <label for="email_recuperar">Email</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="email_recuperar" name="email_recuperar" required
                            autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="sb-nav-link-icon"></div><i class="fas fa-envelope-square"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Recuperar Contraseña</button>

                <!-- Enlace para volver al formulario de inicio de sesión -->
                <div class="text-center mt-3">
                    <a href="index.php" id="linkInicioSesionRecuperar">Volver al inicio de sesión</a>
                </div>
            </form>

            <!-- Formulario de inicio de sesión -->
            <form class="form" id="loginForm" autocomplete="off">
                <!-- Título del formulario -->
                <h4 class="text-center mb-4">Inicio de Sesión</h4>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="email" name="email" required autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="sb-nav-link-icon"></div><i class="fas fa-envelope-square"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="password" name="password" required
                            data-toggle="tooltip" data-placement="top"
                            title="Si no es una empresa puede agregar su Identidad">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <div class="sb-nav-link-icon"></div><i class="fa-solid fa-lock"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>

                <!-- Enlace para ir al formulario de recuperación de contraseña -->
                <div class="text-center mt-3">
                    <a href="#" class="linkRecuperar">¿Olvidaste tu contraseña?</a>
                </div>
                <!-- Enlace para ir al formulario de registro -->
                <div class="text-center mt-3">
                    ¿No tienes una cuenta? Prueba nuestro sistema de monitoreo
                    <a href="#" class="linkRegistro" data-toggle="tooltip" data-placement="top"
                        title="Cree su periodo de prueba de 7 días, gratis">Regístrate aquí</a>
                </div>
            </form>
        </div>

        <!-- Formulario de registro -->
        <div class="register-container" style="display: none">
            <!-- Logo de la empresa -->
            <div class="logo">
                <img src="img/logo.png" alt="CLINICARE" class="logo">
            </div>

            <!-- Título del formulario -->
            <h4 class="text-center mb-4">Registro</h4>

            <form class="form" id="registroForm" autocomplete="off">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_usuario">Empresa <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="empresa_registro" name="empresa_registro"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-building"></i></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="rtn_registro" data-toggle="tooltip" data-placement="top"
                            title="Si no es una empresa puede agregar su Identidad">RTN <span
                                class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" id="rtn_registro" name="rtn_registro" required
                                data-toggle="tooltip" data-placement="top"
                                title="Si no es una empresa puede agregar su Identidad">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_registro">Nombre Completo <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="nombre_registro" name="nombre_registro"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_registro">Email <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="email_registro" name="email_registro" required
                                data-toggle="tooltip" data-placement="top"
                                title="Correo de la Empresa, este se usara para iniciar sesión en el sistema">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fas fa-envelope-square"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono_registro">Teléfono <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="telefono_registro" name="telefono_registro"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-phone"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_registro">Contraseña <span class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="password_registro" name="password_registro"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-lock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label for="password_confirm_registro">Confirmar Contraseña <span
                                class="priority">*<span /></label>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="password_confirm_registro"
                                name="password_confirm_registro" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <div class="sb-nav-link-icon"></div><i class="fa-solid fa-lock"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                <div class="text-center mt-3">
                    ¿Ya tienes una cuenta? <a id="linkInicioSesion" href="#" style="text-decoration: none;">Iniciar
                        Sesión</a>
                </div>

                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <div id="result"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'frontend/footer.php'; ?>

    <!-- Modal de error -->
    <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalErrorLabel">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalErrorMessage">Formato Incorrecto. Inténtalo de nuevo.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de éxito -->
    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccessLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-success text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSuccessLabel">¡Éxito!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalSuccessMessage">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de carga (loading) -->
    <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoadingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces a Bootstrap y jQuery -->
    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/popper.min.js" crossorigin="anonymous"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="fontawesome/js/all.min.js" crossorigin="anonymous"></script>
    <!-- JS personalizado -->
    <script src="asset/js/scriptlogin.js"></script>
</body>

</html>