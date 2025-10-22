$(document).ready(function () {
  function cargarTablaUsuarios() {
<<<<<<< HEAD
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
=======
    // Cargar la tabla desde el PHP
    $("#tablaUsuariosContainer").load(
      "/Biblioteca-2025/views/tabla_usuarios.php",
      function () {
        // Mostrar con animación
        $("#tablaUsuariosContainer").slideDown(400);

        // Inicializar DataTable
        $("#tablausuarios").DataTable({
          language: {
            url: "/Biblioteca-2025/js/es-ES.json",
          },
          destroy: true,
        });

        // Scroll hacia la tabla
        $("html, body").animate(
          {
            scrollTop: $("#tablaUsuariosContainer").offset().top,
          },
          600
        );

        // Asignar click a los botones editar usando delegación
        $("#tablaUsuariosContainer").on("click", ".btnEditar", function () {
          const id = $(this).data("id");

          // Cargar modal desde PHP
          fetch(
            `/Biblioteca-2025/views/modales/modalEditarCliente.php?id=${id}`
          )
            .then((res) => res.text())
            .then((html) => {
              $("#contenedorModal").html(html);

              // Inicializar y mostrar modal
              const modal = new bootstrap.Modal(
                document.getElementById("modalEditarCliente")
              );
              modal.show();

              // Capturar submit del formulario
              $("#formEditarUsuario").on("submit", function (e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch("/controllers/editarUsuarios.php", {
                  method: "POST",
                  body: formData,
                })
                  .then((res) => res.json())
                  .then((data) => {
                    if (data.status === "success") {
                      Swal.fire({
                        icon: "success",
                        title: "¡Éxito!",
                        text: data.message,
                        confirmButtonColor: "#3085d6",
                      }).then(() => {
                        modal.hide();
                        cargarTablaUsuarios(); // recarga tabla dinámicamente
                      });
                    } else {
                      Swal.fire({
                        icon: "error",
                        title: "¡Error!",
                        text: data.message,
                        confirmButtonColor: "#d33",
                      });
                    }
                  })
                  .catch((err) => {
                    console.error(err);
                    Swal.fire({
                      icon: "warning",
                      title: "¡Ups!",
                      text: "Ocurrió un error inesperado.",
                      confirmButtonColor: "#f39c12",
                    });
                  });
              });
            });
        });
>>>>>>> 45dfff3629e6d0fc60b2376d95e897ccafcd8236
      }
    );
  }

<<<<<<< HEAD
=======
  // Clic en tarjeta o menú lateral
>>>>>>> 45dfff3629e6d0fc60b2376d95e897ccafcd8236
  $("#btnUsuarios, #btnUsuariosMenu").on("click", function (e) {
    e.preventDefault();
    cargarTablaUsuarios();
  });
});
