$(document).ready(function () {
  function cargarTablaLibros() {
    // Ocultar tabla de usuarios
    $("#tablaUsuariosContainer").hide();

    // Limpiar contenedor y destruir DataTable si existe
    if ($.fn.DataTable.isDataTable("#tablalibros")) {
      $("#tablalibros").DataTable().destroy();
    }
    $("#tablaLibroscliente").empty();

    // Cargar tabla
    $("#tablaLibroscliente").load(
      "/Biblioteca-2025/views/librosClientes.php",
      function () {
        $("#tablaLibroscliente").slideDown(400);

        $("#tablalibros").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });

        // Aquí tu lógica de botones Editar
      }
    );
  }

  $("#btnCliente, #btnCliente").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});