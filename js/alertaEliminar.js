document.addEventListener("click", function (e) {
  // clic en cualquier boton con clase .btnEliminar
  const button = e.target.closest(".btnEliminar");
  if (!button) return;

  const id = button.dataset.id;
  if (!id) return;

<<<<<<< HEAD
  // cargar el modal de eliminacion desde php
=======
  // Cargar el modal de eliminación desde PHP
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
  fetch("/Biblioteca-2025/views/modales/modalEliminar.php?id=" + id)
    .then((res) => res.text())
    .then((html) => {
      // Insertar el modal en el contenedor
      document.getElementById("contenedorModal").innerHTML = html;

      // Mostrar el modal
      const modalEl = document.getElementById("modalEliminarUsuario");
      const modal = new bootstrap.Modal(modalEl);
      modal.show();

<<<<<<< HEAD
      // boton de confirmar eliminacion dentro del modal
=======
      // Botón de confirmar eliminación dentro del modal
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
      const btnConfirmar = document.getElementById("btnConfirmarEliminar");
      btnConfirmar.addEventListener("click", function () {
        const formData = new FormData();
        formData.append("id", id);

        fetch("/Biblioteca-2025/controllers/eliminarUsuarios.php", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Eliminado",
                text: data.message,
                showConfirmButton: false,
                timer: 1800,
              }).then(() => {
                modal.hide();
                location.reload();
              });
            } else {
              Swal.fire("Error", data.message, "error");
            }
          })
          .catch((err) => {
            console.error("Error:", err);
            Swal.fire(
              "Ups!",
<<<<<<< HEAD
              "ocurrio un error al procesar la eliminacion.",
=======
              "Ocurrio un error al procesar la eliminación.",
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
              "warning"
            );
          });
      });
    })
    .catch((err) => {
      console.error("Error al cargar el modal:", err);
    });
});
