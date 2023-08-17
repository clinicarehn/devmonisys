$(document).ready(function() {
    $('#formClientes #empresa').focus();
    listar_clientes();
});

$("#formClientes").submit(function(event) {
    event.preventDefault();
	var formData = new FormData($(this)[0]); // Create FormData object

    var clientes_id = $("#formClientes #clientes_id").val();
    var empresa = $("#formClientes #empresa").val();
    var rtn = $("#formClientes #rtn").val(); 
	var submitType = $("button[name='submitType']:focus").val(); // Obtener el valor del botón presionado
	
    // Obtener el archivo seleccionado
    var archivo = $("#imagen")[0].files[0];

    // Crear un objeto FormData para enviar datos y archivos
    var formData = new FormData();
    formData.append("submitType", submitType);
    formData.append("clientes_id", clientes_id);
    formData.append("empresa", empresa);
    formData.append("rtn", rtn);
    formData.append("imagen", archivo); // Agregar el archivo al FormData

    // Envío de datos con Ajax a PHP
    $.ajax({
        type: "POST",
        url: "../backend/profile.php",
        data: formData,
		processData: false, // Evitar el procesamiento automático de datos
        contentType: false, // Evitar la configuración automática de contenido		
        success: function(response) {
			if (submitType === "modificar") {
				if (response === "success") {
					$("#formClientes #result").html("<div class='alert alert-success'>Empresa modificada correctamente.</div>");
					getImagenHeader();
				} else if (response.startsWith("error: ")) {
					var errorMessage = response.substring(7);
					$("#formClientes #result").html("<div class='alert alert-danger text-center'>Error: " + errorMessage + "</div>");
				}
            }			

			$('#imagen').val('');
			$("#imagenLabel").text("Seleccione una imágen en formato png");
			// Ocultar el mensaje después de 5 segundos solo si la respuesta es exitosa
			setTimeout(function() {
				$("#formClientes #result").empty(); // Eliminar el contenido del elemento
			}, 5000); // 5000 milisegundos = 5 segundos			
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
			"url":"../backend/llenar_profile.php"
		},
		"columns":[
			{"data":"empresa"},
			{"data":"rtn"},
			{"defaultContent":"<button class='table_editar btn btn-dark'><span class='fa-solid fa-pen-to-square'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "64.33%", targets: 0 },
		  { width: "33.33%", targets: 1 },
		  { width: "2.33%", targets: 2 }
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
}

var editar_clientes_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		
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

// Escuchar el evento de cambio en el input de archivo
$("#imagen").change(function() {
	// Obtener el nombre del archivo seleccionado
	var fileName = $(this).val().split("\\").pop();
	
	// Mostrar el nombre del archivo en el elemento de texto si se seleccionó un archivo,
	// de lo contrario, mostrar el mensaje por defecto
	if (fileName) {
		$("#imagenLabel").text("Imágen seleccionada: " + fileName);
	} else {
		$("#imagenLabel").text("Seleccione una imágen en formato png");
	}
});

// Ocultar el mensaje después de 5 segundos (5000 milisegundos)
setTimeout(function() {
    $("#formClientes #result").empty(); // Eliminar el contenido del elemento
}, 5000); // 5000 milisegundos = 5 segundos 

$("button[name='submitType']").click(function() {
    var submitTypeValue = $(this).val();
    console.log("submitType: " + submitTypeValue);
});