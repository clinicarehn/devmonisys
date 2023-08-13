$(document).ready(function() {
  $('#formHosts #host').focus();
  $('#formClientes #empresa').focus();
  $('#formTipo #grupo').focus();

  obtenerEstados();
  getTipo();
  getRol();

// Definir menuItems fuera de las funciones
var menuItems = {
  "superadmin": ["inicio", "registrar_clientes", "registrar_correos", "registrar_hosts", "registrar_tipos"],
  "admin": ["inicio", "registrar_correos", "registrar_hosts", "Correos"],
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

  // Function to calculate and set top padding for the container
  function setContainerPadding() {
    const headerHeight = $(".custom-header").outerHeight(true);
    $(".container").css("padding-top", headerHeight + "px");
  }

  // Call the function initially and on window resize
  setContainerPadding();
  $(window).resize(function() {
    setContainerPadding();
  });

  // Función para obtener y actualizar el estado de todos los hosts
  function obtenerEstados() {
    $.ajax({
      url: "../backend/obtener_estados.php",
      type: "GET",
      dataType: "json",
      success: function(response) {
        const casasContainer = $("#hostsContainer");
        casasContainer.empty(); // Limpiamos el contenedor de hosts

        // Recorremos cada grupo de hosts (FO y WIFI)
        Object.keys(response).forEach(function(tipo) {
          // Creamos un div para el grupo de hosts
          const grupoHTML = `
            <div class="grupo-hosts">
              <h3>${tipo}</h3>
            </div>
          `;
          casasContainer.append(grupoHTML);

          // Recorremos los hosts dentro del grupo y creamos el HTML para mostrarlos
          response[tipo].forEach(function(host) {
            const estado = host.estado;
            const nombreHost = host.nombre;

            // Creamos el elemento HTML del host con su estado y lo agregamos al grupo
            const hostHTML = `
              <div class="hosts">
                <div class="texto">${nombreHost}</div>
                <div class="icono estado ${estado}"></div>
              </div>
            `;
            casasContainer.find(".grupo-hosts:last-child").append(hostHTML);
          });
        });
      },
      error: function() {
        console.error("Error al obtener los estados de los hosts.");
      }
    });
  }

  // Llamar a obtenerEstados() cada 1 segundo
  setInterval(obtenerEstados, 1000);

  $("#formClientes #btnRegistro").click(function(event) {
    event.preventDefault();
    var empresa = $("#formClientes #empresa").val();
    var rtn = $("#formClientes #rtn").val();
    var email = $("#formClientes #correo").val();
    var pass = $("#formClientes #contrasena").val(); 
    var rols = $("#formClientes #rols").val(); 
    var estado = $("input[name='estado']:checked").val(); // Captura el valor del radio button seleccionado 

    // Validar que la contraseña cumpla con tus criterios de seguridad
    if (pass.length < 8) {
        $("#formClientes #result").html("<div class='alert alert-danger text-center'>La contraseña debe tener al menos 8 caracteres.</div>");
        return;
    }

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_clientes.php",
        data: {
            empresa: empresa,
            rtn: rtn,
            email: email,
            pass: pass ,
            estado: estado,
            rols: rols            
        },
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response === "success") {
                $("#formClientes #result").html("<div class='alert alert-success'>Empresa registrada correctamente.</div>");
                // Limpiar el formulario después de un registro exitoso
                $("#formClientes")[0].reset();
            } else if (response.startsWith("error-existe: ")) {
                $("#formClientes #result").html("<div class='alert alert-danger text-center'>Error al registrar la empresa, este ya existe.</div>");
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#formClientes #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else {
                $("#formClientes #result").html("<div class='alert alert-danger text-center'>Error al registrar la empresa.</div>");
            }
        },
        error: function() {
            $("#formClientes #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
  });

  $("#formHosts #btnRegistro").click(function(event) {
    event.preventDefault();
    var host = $("#formHosts #host").val();
    var ip = $("#formHosts #ip").val();
    var port = $("#formHosts #port").val();
    var ubicacion = $("#formHosts #ubicacion").val();
    var tipo = $("#formHosts #tipo").val(); 
    var estado = $("input[name='estado']:checked").val(); // Captura el valor del radio button seleccionado

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_hosts.php",
        data: {
            host: host,
            ip: ip,
            port: port,
            ubicacion: ubicacion,
            tipo: tipo,
            estado: estado             
        },
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response === "success") {
                $("#formHosts #result").html("<div class='alert alert-success'>Host agregado correctamente.</div>");
                // Limpiar el formulario después de un registro exitoso
                $("#formHosts")[0].reset();
            } else if (response.startsWith("error-existe: ")) {
                $("#formHosts #result").html("<div class='alert alert-danger text-center'>Error al registrar el hosts, este ya existe.</div>");
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#formHosts #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else {
                $("#formHosts #result").html("<div class='alert alert-danger text-center'>Error al registrar el hosts.</div>");
            }
        },
        error: function() {
            $("#formHosts #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
  }); 
  
  $("#formTipo #btnRegistro").click(function(event) {
    event.preventDefault();
    var grupo = $("#formTipo #grupo").val();

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_tipos.php",
        data: {
          grupo: grupo        
        },
        success: function(response) {
            // Manejar la respuesta del servidor
            if (response === "success") {
                $("#formTipo #result").html("<div class='alert alert-success'>Grupo agregado correctamente.</div>");
                // Limpiar el formulario después de un registro exitoso
                $("#formTipo")[0].reset();
            } else if (response.startsWith("error-existe: ")) {
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error al registrar el Grupo, este ya existe.</div>");
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else {
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error al registrar el Grupo.</div>");
            }
        },
        error: function() {
            $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
  }); 

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

  function getTipo(){
    var url = '../backend/getTipo.php';		
		
    $.ajax({
      type: "POST",
      url: url,
      async: true,
      success: function(data){	
        $('#formHosts #tipo').html("");
        $('#formHosts #tipo').html(data);
        $('#formHosts #tipo').selectpicker('refresh');
      }			
    });		
  }

  function getRol(){
    var url = '../backend/getRol.php';		
		
    $.ajax({
      type: "POST",
      url: url,
      async: true,
      success: function(data){	
        $('#formClientes #rols').html("");
        $('#formClientes #rols').html(data);
        $('#formClientes #rols').selectpicker('refresh');
      }			
    });		
  } 
});