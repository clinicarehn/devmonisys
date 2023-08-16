$(document).ready(function() { 
  // Definir menuItems fuera de las funciones
  var menuItems = {
    "superadmin": ["inicio", "registrar_clientes", "registrar_correos", "registrar_hosts", "registrar_tipos", "registrar_usuarios", "historial"],
    "admin": ["inicio", "registrar_correos", "registrar_hosts", "registrar_usuarios", "historial"],
    "user": ["inicio"]
  };

  // Función para verificar permisos según la página actual
  function verificarPermisos(permisosPagina) {
    // Define las páginas excluidas de la verificación
    var paginasExcluidas = [];
    
    var paginaActual = window.location.pathname.split("/").pop().replace(".php", "");
    
    if (!paginasExcluidas.includes(paginaActual) && (!permisosPagina || !permisosPagina.includes(paginaActual))) {
        // Redirigir solo si la página no está en los permisos necesarios
        window.location.href = "pagina_sin_permisos.php";
    }
  }

  // Obtener el rol del usuario
  $.ajax({
    url: "../backend/get_user_role.php", // Ruta correcta hacia tu archivo PHP
    method: "POST",
    success: function (response) {
        var rol = response; // El valor directo del nombre del rol
        ajustarMenuNavegacion(rol);
        verificarPermisos(menuItems[rol]); // Verificar permisos para los permisos de ese rol
    },
    error: function (error) {
        console.error("Error obteniendo el rol del usuario:", error);
    }
  });

  // Función para capitalizar la primera letra de cada palabra
  function capitalizeWords(str) {
    return str.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
  }

  // Función para ajustar el menú de navegación según el rol
  function ajustarMenuNavegacion(rol) {
    // Limpia el menú actual
    $(".navbar-nav").empty();

    // Agrega los elementos del menú según el rol
    menuItems[rol].forEach(function (item) {
      var formattedItem = item.replace(/_/g, ' '); // Reemplazar guiones bajos con espacios
      formattedItem = capitalizeWords(formattedItem); // Capitalizar la primera letra de cada palabra
      formattedItem = formattedItem.replace('SesióN', 'Sesión'); // Corregir "SesióN" a "Sesión"

      if (formattedItem !== 'Cerrar Sesión') {
          var link = $("<a>").addClass("nav-link").attr("href", item + ".php").text(formattedItem);
          var li = $("<li>").addClass("nav-item").append(link);
          $(".navbar-nav").append(li);
      } else {
          var link = $("<a>").addClass("nav-link").attr("href", "#").text(formattedItem); // Enlace vacío para "Cerrar Sesión"
          var li = $("<li>").addClass("nav-item").append(link);
          $(".navbar-nav").append(li);
      }
    });
  }

  $(function () {
    $('[data-toggle="tooltip"]').tooltip({
      trigger: "hover"
    })
  });

  $('.selectpicker').selectpicker();

  // Agrega un evento al botón de "Cerrar Sesión"
  $("#cerrar-sesion").click(function () {
    // Muestra un cuadro de diálogo modal para confirmar el cierre de sesión
		swal({
        title: "¿Esta seguro?",
        text: "Salir del sistema",
        type: "info",
        showCancelButton: true,
        confirmButtonText: "¡Si, deseo salir del sistema!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        allowEscapeKey: false,
        allowOutsideClick: false
      }, function () {
      setTimeout(function () {
        salir();
      }, 1000);
     });    
  });

  function salir(){
    $.ajax({
      url: "../backend/cerrar_sesion.php", // Cambia a la ruta correcta hacia tu archivo PHP de cierre de sesión
      method: "POST",
      success: function () {
        // Redirige al usuario a la página index.html
        window.location.href = "../index.php"; // Cambia a la página que quieras
      }
    });	
  } 
});