$(document).ready(function () {
  function cargarTablaLibros() {
    // Ocultar tabla de usuarios
    $("#tablaUsuariosContainer").hide();

    // Limpiar contenedor y destruir DataTable si existe
    if ($.fn.DataTable.isDataTable("#tablalibros")) {
      $("#tablalibros").DataTable().destroy();
    }
    $("#tablaLibrosContainer").empty();

    // Cargar tabla
    $("#tablaLibrosContainer").load(
      "/Biblioteca-2025/views/tabla_libros.php",
      function () {
        $("#tablaLibrosContainer").slideDown(400);

        $("#tablalibros").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });

        // aqui tu logica de botones editar
      }
    );
  }

  $("#btnLibros, #btnLibrosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});
