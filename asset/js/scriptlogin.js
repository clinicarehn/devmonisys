$(document).ready(function() {
  $("#loginForm #email").focus();
  // Ocultar el formulario de registro al cargar la página
  $("#registroForm").hide();
  $("#recuperarContrasenaForm").hide();

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

// Mostrar el formulario de registro al hacer clic en "Regístrate aquí"
$(".linkRegistro").click(function() {
    $(".form").hide(); // Oculta todos los formularios
    $("#registroForm").show();
    $(".login-container").hide();
    $(".register-container").show();
    $("#registroForm #empresa_registro").focus();
});

// Mostrar el formulario de inicio de sesión al hacer clic en "Iniciar Sesión"
$("#linkInicioSesion").click(function() {
    $("#registroForm").hide();
    $("#loginForm").show();
    $(".login-container").show();
    $(".register-container").hide();    
    $("#loginForm #email").focus();
});

$(".linkRecuperar").click(function() {
    $(".form").hide(); // Oculta todos los formularios
    $("#recuperarContrasenaForm").show(); // Muestra solo el formulario de recuperación de contraseña
    $("#recuperarContrasenaForm #email_recuperar").focus();
});


window.onload = function() {
    $("#loginForm #email").focus();
};

$(function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: "hover"
    })
});

$("#registroForm").submit(function(event) {
    event.preventDefault();
	var formData = new FormData($(this)[0]); // Create FormData object

    var empresa_registro = $("#registroForm #empresa_registro").val();
    var nombre_registro = $("#registroForm #nombre_registro").val();
    var telefono_registro = $("#registroForm #telefono_registro").val();
    var rtn_registro = $("#registroForm #rtn_registro").val();
    var email_registro = $("#registroForm #email_registro").val();
    var password_registro = $("#registroForm #password_registro").val(); 
    var password_confirm_registro = $("#registroForm #password_confirm_registro").val();

    // Validar que la contraseña cumpla con tus criterios de seguridad
    if (password_registro.length < 8) {
        $("#registroForm #result").html("<div class='alert alert-danger text-center'>La contraseña debe tener al menos 8 caracteres.</div>");
        return;
    }

    if (password_confirm_registro.length < 8) {
        $("#registroForm #result").html("<div class='alert alert-danger text-center'>La contraseña debe tener al menos 8 caracteres.</div>");
        return;
    }    

    if (password_registro !== password_confirm_registro) {
        $("#registroForm #result").html("<div class='alert alert-danger text-center'>Las contraseñas no coinciden.</div>");
        return;
    }

    // Crear un objeto FormData para enviar datos y archivos
    var formData = new FormData();
    formData.append("empresa", empresa_registro);
    formData.append("usuario", nombre_registro);
    formData.append("telefono", telefono_registro);
    formData.append("rtn", rtn_registro);
    formData.append("email", email_registro);
    formData.append("pass", password_registro);	

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "backend/registro_cliente.php",
        data: formData,
		processData: false, // Evitar el procesamiento automático de datos
        contentType: false, // Evitar la configuración automática de contenido		
        success: function(response) {
            if (response === "success") {
                $("#registroForm #result").html("<div class='alert alert-success'>Empresa registrada correctamente, ya puede iniciar sesión en su cuenta.</div>");
                $("#registroForm")[0].reset();			
            } else if (response.startsWith("error-existe: ")) {
                var errorMessage = response.substring(13);
                $("#registroForm #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#registroForm #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else {
                $("#registroForm #result").html("<div class='alert alert-danger text-center'>Error al registrar la empresa.</div>");
            }

            // Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#registroForm #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos			
        },
        error: function() {
            $("#registroForm #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
});

// Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#registroForm #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 