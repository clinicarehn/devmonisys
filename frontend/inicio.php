<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio :: MoniSys</title>
    <?php include 'css.php'; ?>
</head>

<body>
    <!-- Llamamos el Header -->
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="d-flex flex-wrap justify-content-center hosts-container" id="hostsContainer">
            <!-- Los hosts se agregarán aquí dinámicamente desde JavaScript -->
        </div>
    </div>

    <!-- Llamamos el Footer -->
    <?php include 'footer.php'; ?>

    <?php include 'script.php'; ?>
    <script src="../asset/js/inicio.js" crossorigin="anonymous"></script>

</body>

</html>