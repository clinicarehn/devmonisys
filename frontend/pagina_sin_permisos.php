<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centrar Contenido</title>
	<style>
		.centered-content {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh; /* Centrar verticalmente en la pantalla */
		}
	</style>
</head>
<body>
    <div class="container-fluid">
        <div id="layoutError">
            <div id="layoutError_content" class="centered-content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="text-center mt-4">
                                    <img class="mb-4 img-permisos" src="../img/error-401.png" />
                                    <p class="lead"><h2>Autorización Requerida</h2></p>
                                    <a class="link-return" href="../frontend/inicio.php">
                                        <i class="fas fa-arrow-left mr-1"></i>
                                        Regresar al Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>
</html>
