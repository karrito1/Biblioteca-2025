$(document).ready(function () {
  // función que carga la tabla dentro del dashboard
  function cargarTablaUsuarios() {
    $("#tablaUsuariosContainer").load(
      "/Biblioteca-2025/views/tabla_usuarios.php",
      function () {
        // Mostrar con animación
        $("#tablaUsuariosContainer").slideDown(400);

        // Inicializar DataTable
        $("#tablausuarios").DataTable({
          language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
          },
        });

        // Desplazar vista hacia la tabla
        $("html, body").animate(
          { scrollTop: $("#tablaUsuariosContainer").offset().top },
          600
        );
      }
    );
  }

  // Clic en la tarjeta del dashboard
  $("#btnUsuarios").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });

  // Clic en el menu lateral
  $("#btnUsuariosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });
});
