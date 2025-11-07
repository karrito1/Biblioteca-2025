$(document).ready(function () {
  function ocultarTablas() {
    $(".seccionTabla").hide(); 
  }

  function cargarTablaLibros() {
    ocultarTablas();

    let contenedor = $("#tablaLibrosContainer");
    contenedor.empty(); 

    contenedor.load("/Biblioteca-2025/views/librosClientes.php", function () {
      contenedor.slideDown(400);

      if ($.fn.DataTable.isDataTable("#tablalibros")) {
        $("#tablalibros").DataTable().destroy();
      }

      $("#tablalibros").DataTable({
        language: { url: "/Biblioteca-2025/js/es-ES.json" },
        autoWidth: false, 
        responsive: true, 
      });
    });
  }

  $("#btnCliente").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});
