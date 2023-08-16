$(document).ready(function() {
    listar_historial();
});

var listar_historial = function(){
	var table_historial = $("#dataTableHistorial").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"../backend/llenar_historial.php"
		},
		"columns":[
			{"data":"fecha"},
			{"data":"empresa"},
			{"data":"nombre"},
			{"data":"host"},
			{"data":"port"},
			{"data":"mensaje"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "14.16%", targets: 0 },
		  { width: "16.16%", targets: 1 },
		  { width: "18.16%", targets: 2 },
		  { width: "16.16%", targets: 3 },
		  { width: "8.16%", targets: 4 },
		  { width: "24.16%", targets: 5 }
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Historial',
				className: 'table_actualizar btn btn-secondary',
				action: 	function(){
					listar_historial();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte de Historial',
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
				title: 'Reporte de Historial',
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
	table_historial.search('').draw();
	$('#buscar').focus();
}