$(document).ready(function() {
    $('#formHosts #host').focus();
    listar_usuarios();
	getRols();
	getClientes();

	if(getRol() === "superadmin"){
		$("#formUsuarios #grupo-user").show();
		$("#formUsuarios #clientes_id").prop("disabled", false);
	}else{
		$("#formUsuarios #grupo-user").hide();
		$("#formUsuarios #clientes_id").prop("disabled", true);		
	}
});

function getRol(){
    var url = '../backend/get_user_role.php';
	var rol;

	$.ajax({
	    type:'POST',
		url:url,
		async: false,
		success:function(data){	
		rol = data;			  		  		  			  
		}
	});
	return rol;
}

$("#formUsuarios").submit(function(event) {
    event.preventDefault();
    var clientes_id = $("#formUsuarios #clientes_id").val();
    var correo = $("#formUsuarios #correo").val();
    var contrasena = $("#formUsuarios #contrasena").val();
    var rols = $("#formUsuarios #rols").val();
    var nombre = $("#formUsuarios #nombre").val(); 
    var estado = $("input[name='estado']:checked").val(); // Captura el valor del radio button seleccionado
	var usuarios_id = $("#formUsuarios #usuarios_id").val(); 

	var submitType = $("button[name='submitType']:focus").val(); // Obtener el valor del botón presionado
    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_usuarios.php",
        data: {
			submitType: submitType, // Enviar el tipo de acción
			usuarios_id: usuarios_id,
			clientes_id: clientes_id,
            correo: correo,
            contrasena: contrasena,
            rols: rols,
            nombre: nombre,
            estado: estado             
        },
        success: function(response) {
			if (submitType === "registrar") {
				if (response === "success") {
					$("#formUsuarios #result").html("<div class='alert alert-success'>Usuario agregado correctamente.</div>");
					$("#formUsuarios")[0].reset();
					listar_usuarios();
					getRols();
					getClientes();
				} else if (response.startsWith("error-existe: ")) {
					var errorMessage = response.substring(13);
					$("#formUsuarios #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formUsuarios #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else {
					$("#formUsuarios #result").html("<div class='alert alert-danger text-center'>Error al registrar el Usuario.</div>");
				}
			}else{
				if (response === "success") {
					$("#formUsuarios #result").html("<div class='alert alert-success'>Usiario modificado correctamente.</div>");
					$("#formUsuarios")[0].reset();
					$('#btnRegistroSave').show();
					$('#btnRegistroEdit').hide();
					$("#formUsuarios #contrasena").prop("disabled", false);
					listar_usuarios();
					getRols();
					getClientes();				
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formUsuarios #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				}
			}

			// Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#formUsuarios #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos
        },
        error: function() {
            $("#formUsuarios #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
});

var listar_usuarios = function(){
	var table_usuarios  = $("#dataTableUsuarios").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_usuarios.php"
		},
		"columns":[
			{"data":"empresa"},
			{"data":"nombre"},
			{"data":"email"},
			{"data":"rol"},
			{"defaultContent":"<button class='table_resetear btn btn-dark'><span class='fa-solid fa-arrows-rotate'></span></button>"},
			{"defaultContent":"<button class='table_editar btn btn-dark'><span class='fa-solid fa-pen-to-square'></span></button>"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark'><span class='fa fa-trash fa-lg'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "25.28%", targets: 0 },
		  { width: "25.28%", targets: 1 },
		  { width: "25.28%", targets: 2 },
		  { width: "14.28%", targets: 3 },
		  { width: "3.28%", targets: 4 },
		  { width: "3.28%", targets: 5 },
		  { width: "3.28%", targets: 6 }		  
		  
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Usuarios',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_usuarios();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Usuarios',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-success',
				exportOptions: {
						columns: [0]
				}					
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				title: 'Reporte de Usuarios',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-danger',
				exportOptions: {
						columns: [0]
				},				
				customize: function ( doc ) {
					doc.content.splice( 1, 0, {
						margin: [ 0, 0, 0, 12 ],
						alignment: 'left',
						image: imagen,
						width:100,
                        height:45
					} );
				}
			}
		]
	});
	table_usuarios.search('').draw();
	$('#buscar').focus();

	resetear_usuario_dataTable("#dataTableUsuarios tbody", table_usuarios);  
	editar_usuario_dataTable("#dataTableUsuarios tbody", table_usuarios);  
	eliminar_usuario_dataTable("#dataTableUsuarios tbody", table_usuarios);
}

var resetear_usuario_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_resetear");
	$(tbody).on("click", "button.table_resetear", function(){
		var data = table.row( $(this).parents("tr") ).data();
		$("#formUsuarios #result").empty();
        resetearContraseña(data.usuarios_id, data.nombre);
	});
}

function resetearContraseña(usuarios_id, nombre){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea resetear la contraseña para el usuario: " + nombre+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el hosts!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		//ResetUser(usuarios_id, nombre);
	});
}

function ResetUser(usuarios_id, nombre) {
    var url = '../backend/reset_users.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			usuarios_id: usuarios_id,        
			nombre: nombre
		},				
        success: function (response) {
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "Contraseña reseteada correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-primary",
                    timer: 3000,
                });
                listar_usuarios();
            }else if (response.startsWith("error-existe: ")) {
				var errorMessage = response.substring(13);
                swal({
                    title: "Error",
                    text: "Error: " + errorMessage,
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });			
			}
			else {
				var errorMessage = response.substring(7);
                swal({
                    title: "Error",
                    text: "Error: " + errorMessage,
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        },
        error: function () {
            swal({
                title: "Error",
                text: "Ha ocurrido un error en la solicitud.",
                type: "error",
                confirmButtonClass: "btn-danger"
            });            
        }
    });
}

var editar_usuario_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		$('#btnRegistroSave').hide();
		$('#btnRegistroEdit').show();			
		$('#formUsuarios')[0].reset();
		$("#formUsuarios #contrasena").prop("disabled", true);
		$("#formUsuarios #result").empty();
		
		var data = table.row( $(this).parents("tr") ).data();
		var url = '../backend/editar_users.php';
		$('#formUsuarios #usuarios_id').val(data.usuarios_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formUsuarios').serialize(),
			success: function(registro){
				var valores = eval(registro);				
				$('#btnRegistroSave').hide();
				$('#btnRegistroEdit').show();
				$('#formUsuarios #clientes_id').val(valores[1]);
				$('#formUsuarios #clientes_id').selectpicker('refresh');	
				$('#formUsuarios #correo').val(valores[3]);				
				$('#formUsuarios #rols').val(valores[4]);
				$('#formUsuarios #rols').selectpicker('refresh');
				$('#formUsuarios #nombre').val(valores[2]);				

				if(valores[5] === "1"){
					$('#formUsuarios #activo').attr('checked', true);
				}else{
					$('#formUsuarios #inactivo').attr('checked', true);
				}
			}
		});
	});
}

var eliminar_usuario_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		$("#formUsuarios #result").empty();
        eliminarUsuario(data.usuarios_id, data.nombre);
	});
}

function eliminarUsuario(usuarios_id, nombre){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar el usuario: " + nombre+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el hosts!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		deleteUser(usuarios_id, nombre);
	});
}

function deleteUser(usuarios_id, nombre) {
    var url = '../backend/delete_users.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			usuarios_id: usuarios_id,        
			nombre: nombre
		},				
        success: function (response) {
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "Usuario eliminado correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-primary",
                    timer: 3000,
                });
                listar_usuarios();
            }else if (response.startsWith("error-existe: ")) {
				var errorMessage = response.substring(13);
                swal({
                    title: "Error",
                    text: "Error: " + errorMessage,
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });			
			}
			else {
				var errorMessage = response.substring(7);
                swal({
                    title: "Error",
                    text: "Error: " + errorMessage,
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        },
        error: function () {
            swal({
                title: "Error",
                text: "Ha ocurrido un error en la solicitud.",
                type: "error",
                confirmButtonClass: "btn-danger"
            });            
        }
    });
}

function getRols(){
	var url = '../backend/getRol.php';		
		
	$.ajax({
		type: "POST",
		url: url,
		async: true,
		success: function(data){	
		$('#formUsuarios #rols').html("");
		$('#formUsuarios #rols').html(data);
		$('#formUsuarios #rols').selectpicker('refresh');
		}			
	});		
} 

function getClientes(){
	var url = '../backend/getClientes.php';		
		
	$.ajax({
		type: "POST",
		url: url,
		async: true,
		success: function(data){	
		$('#formUsuarios #clientes_id').html("");
		$('#formUsuarios #clientes_id').html(data);
		$('#formUsuarios #clientes_id').selectpicker('refresh');
		}			
	});		
}

  // Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formUsuarios #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 