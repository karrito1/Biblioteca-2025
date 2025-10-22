$(document).ready(function () {
  function cargarTablaReservas() {
    // Ocultar otras secciones si las tienes
    $("#tablaUsuariosContainer").hide();
    $("#tablaLibroscliente").hide();

    // Limpiar contenedor y destruir DataTable si existe
    if ($.fn.DataTable.isDataTable("#tablareservas")) {
      $("#tablareservas").DataTable().destroy();
    }
    $("#tablaReservasCliente").empty();

    // Cargar tabla de reservas desde PHP
    $("#tablaReservasCliente").load(
      "/Biblioteca-2025/views/reservasClientes.php", 
      function () {
        $("#tablaReservasCliente").slideDown(400);

        // Inicializar DataTable
        $("#tablareservas").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          responsive: true,
        });

       
      }
    );
  }

  // Cuando el cliente hace clic en el botón “Mis Reservas”
  $("#btnMisReservas").on("click", function (e) {
    e.preventDefault();
    cargarTablaReservas();
  });
});
