<<<<<<< HEAD

$(document).ready(function () {
    function cargarTablaUsuarios() {
        $("#tablaUsuariosContainer").load("/Biblioteca-2025/views/tabla_usuarios.php", function () {
            // Mostrar con animación
            $("#tablaUsuariosContainer").slideDown(400);

            // Inicializar DataTable
            $("#tablausuarios").DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });

            // Desplazar vista hacia la tabla
            $("html, body").animate({
                scrollTop: $("#tablaUsuariosContainer").offset().top
            }, 600);
        });
    }

    // Clic en la tarjeta
    $("#btnUsuarios").on("click", function (e) {
        e.preventDefault();
        cargarTablaUsuarios();
    });

    // Clic en el menú lateral
    $("#btnUsuariosMenu").on("click", function (e) {
        e.preventDefault();
        cargarTablaUsuarios();
    });
});

=======
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

  $("#btnUsuarios, #btnUsuarios").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });
});
>>>>>>> origin/main
