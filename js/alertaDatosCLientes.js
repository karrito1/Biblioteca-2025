document.addEventListener("DOMContentLoaded", function () {
  const formEditar = document.getElementById("formEditarUsuario");
  const modalEditar = document.getElementById("modalEditarUsuario");

  // 🔹 Cargar datos del usuario cuando se abre el modal
  modalEditar.addEventListener("show.bs.modal", function () {
    fetch("/Biblioteca-2025/controllers/obtenerUsuario.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          const usuario = data.data;
          document.getElementById("id_usuario").value = usuario.id;
          document.getElementById("nombreCliente").value = usuario.nombre;
          document.getElementById("emailCliente").value = usuario.email;
          document.getElementById("telefonoCliente").value = usuario.telefono || "";
          document.getElementById("direccionCliente").value = usuario.direccion || "";
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message,
            background: "#202020",
            color: "#fff",
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudieron cargar los datos del usuario.",
          background: "#202020",
          color: "#fff",
        });
      });
  });

  // 🔹 Enviar formulario para actualizar datos
  formEditar.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(formEditar);

    // Mostrar loading
    Swal.fire({
      title: "Actualizando...",
      text: "Por favor espera un momento.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
      background: "#202020",
      color: "#fff",
    });

    fetch("/Biblioteca-2025/controllers/actualizarCliente.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        Swal.close(); // Cierra el loading

        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "¡Actualizado!",
            text: data.message,
            showConfirmButton: false,
            timer: 2000,
            background: "#202020",
            color: "#fff",
          });

          // Cerrar modal
          const modal = bootstrap.Modal.getInstance(modalEditar);
          modal.hide();

          // Actualizar nombre visible en la interfaz si existe
          document.getElementById("nombreUsuarioHeader")?.textContent =
            formData.get("nombreCliente");
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message,
            background: "#202020",
            color: "#fff",
          });
        }
      })
      .catch(() => {
        Swal.close();
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Error al conectar con el servidor.",
          background: "#202020",
          color: "#fff",
        });
      });
  });
});
