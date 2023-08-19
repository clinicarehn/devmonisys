<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login :: CLINICARE Monitoring System</title>
    <!-- Enlaces a Bootstrap, CSS personalizado y JS -->
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="shortcut icon" href="img/icono.png">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <!-- Logo de la empresa -->
            <div class="logo">
            <img src="img/logo.png" alt="CLINICARE" class="logo">
            </div>
            <!-- Formulario de inicio de sesión -->
            <form id="loginForm" autocomplete="off">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
        </div>
    </div>

  <!-- Modal de error -->
  <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorLabel" aria-hidden="true">
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
    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="modalSuccessLabel" aria-hidden="true">
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
    <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoadingLabel" aria-hidden="true">
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
    <script src="asset/js/bootstrap.min.js"></script>
    <!-- JS personalizado -->
    <script src="asset/js/scriptlogin.js"></script>
</body>

</html>
