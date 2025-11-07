$(document).ready(function () {

  function mostrarContenedor(id) {
    $(".contenedor-tabla").hide();   // Oculta TODO
    $("#" + id).show();              // Muestra este contenedor
    $("html, body").animate({ scrollTop: 0 }, "slow");
  }

  function cargarTablaPrestamos() {
    mostrarContenedor("tablaPrestamosContainer");

    // Si ya existe DataTable → destruirla
    if ($.fn.DataTable.isDataTable("#tablaPrestamos")) {
      $("#tablaPrestamos").DataTable().destroy();
    }

    // Limpiar contenedor
    $("#tablaPrestamosContainer").empty();

    // Cargar la vista PHP
    $("#tablaPrestamosContainer").load(
      "/Biblioteca-2025/views/tablaPrestamos.php",
      function () {

        $("#tablaPrestamos").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          scrollX: true,  
          responsive: false
        });

      }
    );
  }

  // Evento de click
  $("#btnPrestamos").on("click", function (e) {
    e.preventDefault();
    cargarTablaPrestamos();
  });
});
