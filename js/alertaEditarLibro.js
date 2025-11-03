document.addEventListener("click", function (e) {
  const button = e.target.closest(".btnEditarLibro");
  if (!button) return;

  const id = button.dataset.id;
  if (!id) return;

  fetch("/Biblioteca-2025/views/modales/modalEditarLibro.php?id=" + id)
    .then((res) => res.text())
    .then((html) => {
      // cargar modal
      document.getElementById("contenedorModal").innerHTML = html;

      const modalEl = document.getElementById("modalEditarLibro");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      // procesar formulario
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
                Swal.fire("Actualizado", "Libro actualizado", "success").then(
                  () => {
                    modal.hide();
                    location.reload();
                  }
                );
              } else {
                Swal.fire("Error", "Error al actualizar", "error");
              }
            })
            .catch((err) => {
              console.error(err);
              Swal.fire("Error", "Error al procesar solicitud", "error");
            });
        },
        { once: true }
      );
    });
});
