$(document).ready(function () {
  function cargarTablaUsuarios() {
    // Ocultar tabla de libros
    $("#tablaLibrosContainer").hide();

    // Limpiar contenedor y destruir DataTable si existe
    if ($.fn.DataTable.isDataTable("#tablausuarios")) {
      $("#tablausuarios").DataTable().destroy();
    }
    $("#tablaUsuariosContainer").empty();

    // Cargar tabla
    $("#tablaUsuariosContainer").load(
      "/Biblioteca-2025/views/tabla_usuarios.php",
      function () {
        $("#tablaUsuariosContainer").slideDown(400);

        $("#tablausuarios").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });
      }
    );
  }

  $("#btnUsuarios, #btnUsuariosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });
});
