$(document).ready(function () {
  function mostrarContenedor(id) {
    $(".contenedor-tabla").hide(); // Oculta todos los contenedores
    $("#" + id).show(); // Muestra solo este
    $("html, body").animate({ scrollTop: 0 }, "slow");
  }

  function cargarTablaUsuarios() {
    mostrarContenedor("tablaUsuariosContainer");

    // Si DataTable ya existe → destruir antes de recargar
    if ($.fn.DataTable.isDataTable("#tablausuarios")) {
      $("#tablausuarios").DataTable().destroy();
    }

    // Limpiar contenedor
    $("#tablaUsuariosContainer").empty();

    // Cargar la vista PHP
    $("#tablaUsuariosContainer").load(
      "/Biblioteca-2025/views/tabla_usuarios.php",
      function () {
        
        $("#tablausuarios").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          scrollX: true, 
          responsive: false, 
        });
      }
    );
  }

  $("#btnUsuarios").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });
});
