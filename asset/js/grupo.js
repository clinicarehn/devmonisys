$(document).ready(function() {
	$('#formTipo #grupo').focus();
    listar_grupo();
});

$("#formTipo").submit(function(event) {
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
				listar_grupo();
            } else if (response.startsWith("error-existe: ")) {
				var errorMessage = response.substring(13);
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else if (response.startsWith("error: ")) {
                var errorMessage = response.substring(7);
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
            } else {
                $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error al registrar el Grupo.</div>");
            }

			// Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#formTipo #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos			
        },
        error: function() {
            $("#formTipo #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
  });


var listar_grupo = function(){
	var table_grupo  = $("#dataTableGrupo").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_grupo.php"
		},
		"columns":[
			{"data":"nombre"},
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
				titleAttr: 'Actualizar Grupo',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_grupo();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Grupo',
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
				title: 'Reporte de Grupo',
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
	table_grupo.search('').draw();
	$('#buscar').focus();

	eliminar_grupo_dataTable("#dataTableGrupo tbody", table_grupo);
}

var eliminar_grupo_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		$("#formTipo #result").empty();
        eliminarGrupo(data.tipos_id, data.nombre);
	});
}

function eliminarGrupo(tipos_id, nombre){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este correo: " + nombre+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el correo!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		deleteGrupo(tipos_id, nombre);
	});
}

function deleteGrupo(tipos_id, nombre) {
    var url = '../backend/delete_grupo.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			tipos_id: tipos_id,        
			nombre: nombre
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
                listar_grupo();
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

// Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formTipo #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 