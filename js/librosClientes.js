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
<<<<<<< HEAD
    $("#tablaLibroscliente").load(
=======
    $("#  ").load(
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
      "/Biblioteca-2025/views/librosClientes.php",
      function () {
        $("#tablaLibroscliente").slideDown(400);

        $("#tablalibros").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
        });

<<<<<<< HEAD
        // aqui tu logica de botones editar
=======
        // Aquí tu lógica de botones Editar
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
      }
    );
  }

  $("#btnCliente, #btnCliente").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});