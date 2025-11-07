$(document).ready(function () {
  function mostrarContenedor(id) {
    $(".contenedor-tabla").hide(); // Oculta todos
    $("#" + id).show(); // Muestra solo el necesario
    $("html, body").animate({ scrollTop: 0 }, "slow");
  }

  function cargarTablaLibros() {
    mostrarContenedor("tablaLibrosContainer");

    // Si DataTable ya existe, destruirlo
    if ($.fn.DataTable.isDataTable("#tablalibros")) {
      $("#tablalibros").DataTable().destroy();
    }

    // Limpiar contenido
    $("#tablaLibrosContainer").empty();

    // Cargar tabla
    $("#tablaLibrosContainer").load(
      "/Biblioteca-2025/views/tabla_libros.php",
      function () {
       
        $("#tablalibros").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          scrollX: true, 
          responsive: false, 
        });
      }
    );
  }

  // Soporte para botones de Admin y Cliente
  $("#btnLibros, #btnLibrosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});
