$(document).ready(function() {
	obtenerEstados();
	
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

  setInterval(obtenerEstados, 120000); // Ejecutar cada 2 minutos (300,000 milisegundos)
});