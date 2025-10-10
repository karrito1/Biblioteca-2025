document.querySelectorAll(".btn-editar").forEach((btn) => {
  btn.addEventListener("click", async () => {
    const id = btn.getAttribute("data-id");

    try {
      const res = await fetch(
        `../views/modificar_registro.php?idempleados=${id}`
      );
      const html = await res.text();

      const contenedor = document.getElementById("contenidoEditar");
      contenedor.innerHTML = html;

      // Mostrar modal
      const modal = new bootstrap.Modal(document.getElementById("modalEditar"));
      modal.show();

      // Esperar a que se cargue el formulario
      const form = contenedor.querySelector("#formModificar");
      if (form) {
        form.addEventListener("submit", function (e) {
          e.preventDefault();
          const formData = new FormData(form);

          Swal.fire({
            title: "¿Confirmar acción?",
            text: "¿Deseas guardar los cambios?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, guardar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            reverseButtons: true,
          }).then((result) => {
            if (result.isConfirmed) {
              fetch(form.action, {
                method: "POST",
                body: formData,
              })
                .then((res) => res.json())
                .then((data) => {
                  if (data.status === "ok") {
                    Swal.fire({
                      icon: "success",
                      title: "¡Actualizado!",
                      text: data.message,
                      timer: 2000,
                      showConfirmButton: false,
                    }).then(() => location.reload());
                  } else {
                    Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: data.message,
                    });
                  }
                })
                .catch((err) => {
                  console.error(err);
                  Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: "No se pudo procesar la solicitud",
                  });
                });
            }
          });
        });
      }
    } catch (err) {
      console.error("Error al cargar el formulario:", err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo cargar el formulario de edición",
      });
    }
  });
});
