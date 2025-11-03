$(document).ready(function () {
  function cargarTablaLibros() {
    $("#tablaUsuariosContainer").hide();

    if ($.fn.DataTable.isDataTable("#tablalibros")) {
      $("#tablalibros").DataTable().destroy();
    }
    $("#tablaLibrosContainer").empty();

    $("#tablaLibrosContainer").load(
      "/Biblioteca-2025/views/tabla_libros.php",
      function () {
        $("#tablaLibrosContainer").slideDown(400);

        $("#tablalibros").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });

        // Aquí tu lógica de botones Editar
      }
    );
  }

  $("#btnLibros, #btnLibrosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});
