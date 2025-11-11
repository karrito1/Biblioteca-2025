$(document).ready(function () {
  function mostrarContenedor(id) {
    $(".contenedor-tabla").hide();
    $("#" + id).show();
    $("html, body").animate({ scrollTop: 0 }, "slow");
  }

  function cargarTablaInventario() {
    mostrarContenedor("tablaInventarioContainer");

    if ($.fn.DataTable.isDataTable("#tblinventario")) {
      $("#tblinventario").DataTable().destroy();
    }

    $("#tablaInventarioContainer").empty();

    $("#tablaInventarioContainer").load(
      "/Biblioteca-2025/views/tablaInventario.php",
      function () {
        $("#tblinventario").DataTable({
          language: { url: "/Biblioteca-2025/js/es-ES.json" },
          destroy: true,
          scrollX: true,
          responsive: false,
        });
      }
    );
  }

  $("#btnInventario,#btnInventario").on("click", function (e) {
    e.preventDefault();
    cargarTablaInventario();
  });
});
