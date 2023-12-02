$(document).ready(function() {
    $('#formHosts #host').focus();
	getTipo();
    listar_hosts();
});

$("#formHosts").submit(function(event) {
    event.preventDefault();
    var host = $("#formHosts #host").val();
    var ip = $("#formHosts #ip").val();
    var port = $("#formHosts #port").val();
    var ubicacion = $("#formHosts #ubicacion").val();
    var tipo = $("#formHosts #tipo").val(); 
    var estado = $("input[name='estado']:checked").val(); // Captura el valor del radio button seleccionado
	var hosts_id = $("#formHosts #hosts_id").val(); 

	var submitType = $("button[name='submitType']:focus").val(); // Obtener el valor del botón presionado
    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/registrar_hosts.php",
        data: {
			submitType: submitType, // Enviar el tipo de acción
			hosts_id: hosts_id,
            host: host,
            ip: ip,
            port: port,
            ubicacion: ubicacion,
            tipo: tipo,
            estado: estado             
        },
        success: function(response) {
			if (submitType === "registrar") {
				if (response === "success") {
					$("#formHosts #result").html("<div class='alert alert-success'>Host agregado correctamente.</div>");
					// Limpiar el formulario después de un registro exitoso
					$("#formHosts")[0].reset();
					listar_hosts();
					getTipo();
				} else if (response.startsWith("error-existe: ")) {
					var errorMessage = response.substring(13);
					$("#formHosts #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formHosts #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				} else {
					$("#formHosts #result").html("<div class='alert alert-danger text-center'>Error al registrar el hosts.</div>");
				}
			}else{
				if (response === "success") {
					$("#formHosts #result").html("<div class='alert alert-success'>Host modificado correctamente.</div>");
					$("#formHosts")[0].reset();
					$('#btnRegistroSave').show();
					$('#btnRegistroEdit').hide();
					listar_hosts();	
					getTipo();			
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formHosts #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				}
			}

			// Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#formHosts #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos			
        },
        error: function() {
            $("#formHosts #result").html("<div class='alert alert-danger text-center'>Error en el servidor. Inténtalo nuevamente más tarde.</div>");
        }
    });
});

var listar_hosts = function(){
	var table_hosts  = $("#dataTableHosts").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_hosts.php"
		},
		"columns":[
			{"data":"empresa"},
			{"data":"nombre"},
			{"data":"host"},
			{"data":"port"},
			{"data":"grupo"},
			{"data":"ubicacion"},
			{"defaultContent":"<button class='table_editar btn btn-dark'><span class='fa-solid fa-pen-to-square'></span></button>"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark'><span class='fa fa-trash fa-lg'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "24.28%", targets: 0 },
		  { width: "24.28%", targets: 1 },
		  { width: "18.28%", targets: 2 },
		  { width: "10.28%", targets: 3 },
		  { width: "10.28%", targets: 4 },
		  { width: "22.28%", targets: 5 },
		  { width: "2.28%", targets: 6 },
		  { width: "2.28%", targets: 7 }
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Hosts',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_hosts();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Hosts',
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
				title: 'Reporte de Hosts',
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
	table_hosts.search('').draw();
	$('#buscar').focus();

	editar_hosts_dataTable("#dataTableHosts tbody", table_hosts);  
	eliminar_hosts_dataTable("#dataTableHosts tbody", table_hosts);
}

var editar_hosts_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		$('#btnRegistroSave').hide();
		$('#btnRegistroEdit').show();			
		$('#formHosts')[0].reset();
		$("#formHosts #result").empty();
		
		var data = table.row( $(this).parents("tr") ).data();
		var url = '../backend/editar_hosts.php';
		$('#formHosts #hosts_id').val(data.hosts_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formHosts').serialize(),
			success: function(registro){
				var valores = eval(registro);				
				$('#btnRegistroSave').hide();
				$('#btnRegistroEdit').show();
				$('#formHosts #host').val(valores[4]);
				$('#formHosts #ip').val(valores[2]);				
				$('#formHosts #port').val(valores[3]);
				$('#formHosts #ubicacion').val(valores[5]);				
				$('#formHosts #tipo').val(valores[7]);
				$('#formHosts #tipo').selectpicker('refresh');

				if(valores[6] == 1){
					$('#formHosts #activo').attr('checked', true);
				}else{
					$('#formHosts #inactivo').attr('checked', true);
				}

				if(valores[8] == 1){
					$('#formHosts #activo_si').attr('checked', true);
				}else{
					$('#formHosts #activo_no').attr('checked', true);
				}				
			}
		});
	});
}

var eliminar_hosts_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		$("#formHosts #result").empty();
        eliminarCorre(data.hosts_id, data.host);
	});
}

function eliminarCorre(hosts_id, host){
	swal({
	  title: "¿Estas seguro?",
	  text: "¿Desea eliminar este host: " + host+ "?",
	  type: "info",
	  showCancelButton: true,
	  confirmButtonClass: "btn-primary",
	  confirmButtonText: "¡Sí, eliminar el hosts!",
	  cancelButtonText: "Cancelar",
	  closeOnConfirm: false
	},
	function(){
		deleteEmail(hosts_id, host);
	});
}

function deleteEmail(hosts_id, host) {
    var url = '../backend/delete_hosts.php';

    $.ajax({
        type: 'POST',
        url: url,
        data: {
			hosts_id: hosts_id,        
			host: host
		},				
        success: function (response) {
            if (response === "success") {
                swal({
                    title: "Success",
                    text: "El host se elimino correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-primary",
                    timer: 3000,
                });
                listar_hosts();
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

  // Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formHosts #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 