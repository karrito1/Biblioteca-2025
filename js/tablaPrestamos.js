$(document).ready(function () {
  function cargarTablaPrestamos() {
    // Ocultar tabla de usuarios
    $("#tablaUsuariosContainer").hide();
    $("#tablaReservasCliente").hide();
    $("#tablaLibroscliente").hide();

    // Limpiar contenedor y destruir DataTable si existe
    if ($.fn.DataTable.isDataTable("#tablaPrestamos")) {
      $("#tablaPrestamos").DataTable().destroy();
    }
    $("#tablaPrestamosContainer").empty();

    // Cargar tabla
    $("#tablaPrestamosContainer").load(
      "/Biblioteca-2025/views/tablaPrestamos.php",
      function () {
        $("#tablaPrestamosContainer").slideDown(400);

        $("#tablaPrestamos").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });

        // Aquí tu lógica de botones Editar o Eliminar
      }
    );
  }

  $("#btnPrestamos, #btnPrestamos").on("click", function (e) {
    e.preventDefault();
    cargarTablaPrestamos();
  });
});
