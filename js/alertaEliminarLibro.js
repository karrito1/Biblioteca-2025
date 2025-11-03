document.addEventListener("click", function (e) {
  const button = e.target.closest(".btnEliminarLibro");
  if (!button) return;

  const id = button.dataset.id;
  if (!id) return;

  // Confirmación con SweetAlert
  Swal.fire({
    title: "¿Eliminar libro?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
  }).then((result) => {
    if (!result.isConfirmed) return;

    // Llamada al controlador
    const formData = new FormData();
    formData.append("id", id);

    fetch("/Biblioteca-2025/controllers/eliminarLibro.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire("¡Eliminado!", data.message, "success").then(() => {
            location.reload(); // recarga la tabla
          });
        } else {
          Swal.fire("Error", data.message, "error");
        }
      })
      .catch((err) => {
        console.error(err);
        Swal.fire(
          "Ups!",
          "Ocurrió un error al procesar la solicitud.",
          "error"
        );
      });
  });
});
