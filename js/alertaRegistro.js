document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formRegistro");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    formData.append("btn_registrar", "ok");

    // 💬 Confirmación antes de registrar
    Swal.fire({
      title: "¿Confirmar registro?",
      text: "Se guardará un nuevo usuario en el sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, registrar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      reverseButtons: true,
    }).then((result) => {
      if (!result.isConfirmed) return;

      // 📨 Envío de datos al servidor
      fetch("/Biblioteca-2025/controllers/agregarUsuarios.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              icon: "success",
              title: "¡Usuario registrado!",
              text: data.message,
              showConfirmButton: false,
              timer: 1800,
              background: "#f0f9f0",
              color: "#155724",
            });

            form.reset();

            // 🔒 Cierra el modal
            const modalElement = document.getElementById("modalRegistrarUsuario");
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();

            // 🔄 Recarga la página suavemente
            setTimeout(() => location.reload(), 1800);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error al registrar",
              text: data.message || "No se pudo completar el registro.",
              confirmButtonColor: "#d33",
              background: "#fff5f5",
            });
          }
        })
        .catch((error) => {
          console.error("Error en la petición:", error);
          Swal.fire({
            icon: "error",
            title: "Error del servidor",
            text: "No se pudo contactar con el servidor.",
            confirmButtonColor: "#d33",
          });
        });
    });
  });
});

