$(document).ready(function() {
    $('#formCorreos #email').focus();
    listar_correos();
});

$("#formCorreos").submit(function(event) {
    event.preventDefault();
    var email = $("#formCorreos #email").val();

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_correos.php",
        data: {
            email: email        
        },
        success: function(response) {
            if (response === "success") {
                $("#formCorreos #result").html("<div class='alert alert-success'>Correo agregado correctamente.</div>");
                // Limpiar el formulario después de un registro exitoso
                $("#formCorreos")[0].reset();
                $("#formCorreos #email").focus();
                listar_correos();
            } else if (response.startsWith("error-existe: ")) {
				var errorMessage = response.substring(13);
                $("#formCorreos #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
                $("#formCorreos #email").focus();
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#formCorreos #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
                $("#formCorreos #email").focus();
            } else {
                $("#formCorreos #result").html("<div class='alert alert-danger text-center'>Error al registrar el correo.</div>");
                $("#formCorreos #email").focus();
            }

			// Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#formCorreos #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos			
        },
        error: function() {
            $("#formCorreos #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
}); 

var listar_correos = function(){
	var table_correos  = $("#dataTableCorreos").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_correos.php"
		},
		"columns":[
			{"data":"email"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark'><span class='fa fa-trash fa-lg'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "98%", targets: 0 },
		  { width: "2%", targets: 1 },
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Correo',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_correos();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Correo',
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
				title: 'Reporte de Correo',
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
	table_correos.search('').draw();
	$('#buscar').focus();

	eliminar_correo_dataTable("#dataTableCorreos tbody", table_correos);  
}

var eliminar_correo_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		$("#formCorreos #result").empty();
        eliminarCorreo(data.email, data.clientes_id);
	});
}

function eliminarCorreo(email, clientes_id){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este correo: " + email+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el correo!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		deleteEmail(email, clientes_id);
	});
}

function deleteEmail(email, clientes_id) {
    var url = '../backend/delete_email.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			email: email,        
			clientes_id: clientes_id
		},
        success: function (response) {
            // Verificar la respuesta del servidor y mostrar el SweetAlert correspondiente
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "El correo se elimino correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-primary",
                    timer: 3000,
                });
                listar_correos();
            } else {
                swal({
                    title: "Error",
                    text: "El correo no se puede eliminar.",
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

// Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formCorreos #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 