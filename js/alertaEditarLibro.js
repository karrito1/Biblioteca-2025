document.addEventListener("click", function (e) {
  const button = e.target.closest(".btnEditarLibro"); // botón de editar libro
  if (!button) return;

  const id = button.dataset.id;
  if (!id) return;

  // Cargar modal desde PHP pasando el id
  fetch("/Biblioteca-2025/views/modales/modalEditarLibro.php?id=" + id)
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("contenedorModal").innerHTML = html;

      // Mostrar modal
      const modalEl = document.getElementById("modalEditarLibro");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      // Enviar formulario
      const form = document.getElementById("formEditarLibro");
      form.addEventListener(
        "submit",
        function (e) {
          e.preventDefault();
          const formData = new FormData(form);

          fetch("/Biblioteca-2025/controllers/editarLibro.php", {
            method: "POST",
            body: formData,
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.status === "success") {
                Swal.fire("¡Éxito!", data.message, "success").then(() => {
                  modal.hide();
                  location.reload();
                });
              } else {
                Swal.fire("Error", data.message, "error");
              }
            })
            .catch((err) => {
              console.error(err);
              Swal.fire(
                "Ups!",
                "Ocurrió un error al procesar la respuesta del servidor.",
                "warning"
              );
            });
        },
        { once: true }
      );
    });
});
