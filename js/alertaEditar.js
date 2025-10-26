document.addEventListener("click", function (e) {
  const button = e.target.closest(".btnEditar");
  if (!button) return;

  const id = button.dataset.id;
  if (!id) return;

  fetch("/Biblioteca-2025/views/modales/modalEditar.php?id=" + id)
    .then((res) => res.text())
    .then((html) => {
      document.getElementById("contenedorModal").innerHTML = html;

      const modalEl = document.getElementById("modalEditarCliente");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

      const form = document.getElementById("formEditarUsuario");
      form.addEventListener(
        "submit",
        function (e) {
          e.preventDefault();
          const formData = new FormData(form);

          fetch("/Biblioteca-2025/controllers/editarUsuarios.php", {
            method: "POST",
            body: formData,
          })
            .then((res) => res.json())
            .then((data) => {
              if (data.status === "success") {
<<<<<<< HEAD
                Swal.fire("exito!", data.message, "success").then(() => {
                  modal.hide();
                  location.reload(); // o actualizar tabla dinamicamente
=======
                Swal.fire("¡Éxito!", data.message, "success").then(() => {
                  modal.hide();
                  location.reload(); // o actualizar tabla dinámicamente
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                });
              } else {
                Swal.fire("Error", data.message, "error");
              }
            })
            .catch((err) => {
              console.error(err);
              Swal.fire(
                "Ups!",
<<<<<<< HEAD
                "ocurrio un error al procesar la respuesta del servidor.",
=======
                "Ocurrió un error al procesar la respuesta del servidor.",
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                "warning"
              );
            });
        },
        { once: true }
      ); 
    });
});
