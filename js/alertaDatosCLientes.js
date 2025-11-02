document.addEventListener("DOMContentLoaded", function () {
  const formEditar = document.getElementById("formEditarUsuario");
  const modalEditar = document.getElementById("modalEditarUsuario");

  // Cargar datos del usuario cuando se abre el modal
  modalEditar.addEventListener("show.bs.modal", function () {
    fetch("/Biblioteca-2025/controllers/obtenerUsuario.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.success && data.usuario) {
          document.getElementById("nombre").value = data.usuario.nombre || "";
          document.getElementById("email").value = data.usuario.email || "";
          document.getElementById("telefono").value =
            data.usuario.telefono || "";
          document.getElementById("direccion").value =
            data.usuario.direccion || "";
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar tus datos.",
            confirmButtonColor: "#d33",
          });
        }
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Error al conectar con el servidor.",
          confirmButtonColor: "#d33",
        });
      });
  });

  // Guardar cambios
  formEditar.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("/Biblioteca-2025/controllers/actualizarCliente.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "¡Actualizado!",
            text: "Tus datos han sido actualizados correctamente.",
            confirmButtonColor: "#28a745",
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message || "No se pudo actualizar la información.",
            confirmButtonColor: "#d33",
          });
        }
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Error al actualizar tus datos.",
          confirmButtonColor: "#d33",
        });
      });
  });
});
