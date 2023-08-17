$(document).ready(function() {
  $("#loginForm #email").focus();

  $("#loginForm").submit(function(event) {
    event.preventDefault();
    var email = $("#email").val();
    var password = $("#password").val(); // Contraseña sin hash MD5

        $.ajax({
            type: "POST",
            url: "backend/login.php", // Ruta al archivo PHP que maneja el inicio de sesión
            data: {
                email: email,
                password: password
            },
            dataType: 'json', // Espera una respuesta JSON
            success: function(response) {
                if (response.message === "success") {
                    // Inicio de sesión exitoso
                    window.location.href = "frontend/inicio.php"; // Redirigir a la página de inicio
                } else {
                    $("#modalErrorMessage").text(response.message);
                    $("#modalError").modal("show");
                }
            },
            error: function() {
                $("#modalErrorMessage").text("Error en el servidor. Inténtalo de nuevo más tarde.");
                $("#modalError").modal("show");
            }
        });
    }); 
});

window.onload = function() {
    $("#loginForm #email").focus();
};