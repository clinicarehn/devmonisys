$(document).ready(function() {
    $('#formClientes #empresa').focus();
	getRol();
    listar_clientes();
});

$("#formClientes").submit(function(event) {
    event.preventDefault();
    var clientes_id = $("#formClientes #clientes_id").val();
	var empresa = $("#formClientes #empresa").val();
    var rtn = $("#formClientes #rtn").val();
    var email = $("#formClientes #correo").val();
    var pass = $("#formClientes #contrasena").val(); 
    var rols = $("#formClientes #rols").val(); 
    var estado = $("input[name='estado']:checked").val(); // Captura el valor del radio button seleccionado 

	if ($("#formClientes #contrasena").prop("disabled")) {
		console.log("El campo está desactivado");
	} else {
		// Validar que la contraseña cumpla con tus criterios de seguridad
		if (pass.length < 8) {
			$("#formClientes #result").html("<div class='alert alert-danger text-center'>La contraseña debe tener al menos 8 caracteres.</div>");
			return;
		}

		console.log("El campo está activado");
	}

	var submitType = $("button[name='submitType']:focus").val(); // Obtener el valor del botón presionado

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_clientes.php",
        data: {
			submitType: submitType, // Enviar el tipo de acción
            clientes_id: clientes_id,
			empresa: empresa,
            rtn: rtn,
            email: email,
            pass: pass ,
            estado: estado,
            rols: rols            
        },
        success: function(response) {
			if (submitType === "registrar") {
				if (response === "success") {
					$("#formClientes #result").html("<div class='alert alert-success'>Empresa registrada correctamente.</div>");
					$("#formClientes")[0].reset();
					listar_clientes();				
				} else if (response.startsWith("error-existe: ")) {
					var errorMessage = response.substring(13);
					$("#formClientes #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formClientes #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else {
					$("#formClientes #result").html("<div class='alert alert-danger text-center'>Error al registrar la empresa.</div>");
				}
            } else if (submitType === "modificar") {
				if (response === "success") {
					$("#formClientes #result").html("<div class='alert alert-success'>Empresa modificada correctamente.</div>");
					$("#formClientes")[0].reset();
					$('#btnRegistroSave').show();
					$('#btnRegistroEdit').hide();
					$("#formClientes #grupo-user").hide();
					listar_clientes();
					$('#btnRegistroSave').hide();
					$('#btnRegistroEdit').show();	
					addValidate();
					$('#btnRegistroSave').show();
					$('#btnRegistroEdit').hide();					
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formClientes #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				}
            }
        },
        error: function() {
            $("#formClientes #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
});

var listar_clientes = function(){
	var table_clientes  = $("#dataTableClientes").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_clientes.php"
		},
		"columns":[
			{"data":"empresa"},
			{"data":"rtn"},
			{"defaultContent":"<button class='table_editar btn btn-dark'><span class='fa-solid fa-pen-to-square'></span></button>"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark'><span class='fa fa-trash fa-lg'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "58%", targets: 0 },
		  { width: "38%", targets: 1 },
		  { width: "2%", targets: 2 },
		  { width: "2%", targets: 3 }
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Clientes',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_clientes();
					$('#btnRegistroSave').show();
					$('#btnRegistroEdit').hide();
					addValidate();
					$("#formClientes")[0].reset();				
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Clientes',
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
				title: 'Reporte de Clientes',
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
	table_clientes.search('').draw();
	$('#buscar').focus();

	editar_clientes_dataTable("#dataTableClientes tbody", table_clientes);  
	eliminar_clientes_dataTable("#dataTableClientes tbody", table_clientes);
}

function removeValidate(){
	$("#formClientes #grupo-user").hide();

	$("#formClientes #correo").prop("disabled", true);
	$("#formClientes #contrasena").prop("disabled", true);
	$("#formClientes #rols").prop("disabled", true);
}

function addValidate(){
	$("#formClientes #grupo-user").show();

	$("#formClientes #correo").prop("disabled", false);
	$("#formClientes #contrasena").prop("disabled", false);
	$("#formClientes #rols").prop("disabled", false);
}

var editar_clientes_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		removeValidate();
		$('#btnRegistroSave').hide();
		$('#btnRegistroEdit').show();	
		
		$('#formClientes')[0].reset();
		
		var data = table.row( $(this).parents("tr") ).data();
		var url = '../backend/editar_clientes.php';
		$('#formClientes #clientes_id').val(data.clientes_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formClientes').serialize(),
			success: function(registro){
				var valores = eval(registro);				
				$('#btnRegistroSave').hide();
				$('#btnRegistroEdit').show();
				$('#formClientes #empresa').val(valores[0]);
				$('#formClientes #rtn').val(valores[2]);				

				if(valores[3] == 1){
					$('#formClientes #activo').attr('checked', true);
				}else{
					$('#formClientes #inactivo').attr('checked', false);
				}
			}
		});
	});
}

var eliminar_clientes_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();

        eliminarCorre(data.clientes_id, data.empresa);
	});
}

function eliminarCorre(clientes_id, empresa){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este cliente: " + empresa+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el cliente!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		deleteEmail(clientes_id, empresa);
	});
}

function deleteEmail(clientes_id, empresa) {
    var url = '../backend/delete_clientes.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			clientes_id: clientes_id,        
			empresa: empresa
		},
        success: function (response) {
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "El cliente se elimino correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-primary",
                    timer: 3000,
                });
                listar_correos();
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

// Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formClientes #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 