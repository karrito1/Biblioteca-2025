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

<<<<<<< HEAD
  // cuando el cliente hace clic en el boton "mis reservas"
=======
  // Cuando el cliente hace clic en el botón “Mis Reservas”
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
  $("#btnMisReservas").on("click", function (e) {
    e.preventDefault();
    cargarTablaReservas();
  });
});
