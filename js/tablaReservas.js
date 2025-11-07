$(document).ready(function () {
  function mostrarContenedor(id) {
    $(".contenedor-tabla").hide(); // Oculta todos los contenedores
    $("#" + id).show(); // Muestra el que necesito
    $("html, body").animate({ scrollTop: 0 }, "slow"); // Sube al inicio
  }

  function cargarTablaReservas() {
    mostrarContenedor("tablaReservasCliente");

    // Si ya existe DataTable, destruirlo antes de recargar
    if ($.fn.DataTable.isDataTable("#tablareservas")) {
      $("#tablareservas").DataTable().destroy();
    }

    // Limpiar tabla antes de volver a cargar
    $("#tablaReservasCliente").empty();

    // Cargar tabla desde la vista PHP
    $("#tablaReservasCliente").load(
      "/Biblioteca-2025/views/tablaReservas.php",
      function () {
        // Inicializar DataTable con scroll habilitado
        $("#tablareservas").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          scrollX: true, 
          responsive: false, 
        });
      }
    );
  }

  // Evento del botón “Mis Reservas”
  $("#btnMisReservas").on("click", function (e) {
    e.preventDefault();
    cargarTablaReservas();
  });
});
