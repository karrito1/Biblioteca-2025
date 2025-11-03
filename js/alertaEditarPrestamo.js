document.addEventListener("DOMContentLoaded", () => {
  // Botón editar
  document.addEventListener("click", async (e) => {
    if (!e.target.closest(".btnEditarPrestamo")) return;

    const boton = e.target.closest(".btnEditarPrestamo");
    const prestamoId = boton.getAttribute("data-id");

    try {
      const res = await fetch(
        `/Biblioteca-2025/controllers/obtenerPrestamo.php?id=${prestamoId}`
      );
      const data = await res.json();

      if (!data.success) {
        Swal.fire("Error", "No se pudo cargar la información.", "error");
        return;
      }

      const prestamo = data.prestamo;

      // Rellenar campos
      document.getElementById("editar_prestamo_id").value = prestamo.id;
      document.getElementById("editar_usuario_id").innerHTML = data.usuarios;
      document.getElementById("editar_usuario_id").value = prestamo.usuario_id;

      document.getElementById("editar_libro_id").innerHTML = data.libros;
      document.getElementById("editar_libro_id").value = prestamo.libro_id;

      document.getElementById("editar_fecha_prestamo").value =
        prestamo.fecha_prestamo;
      document.getElementById("editar_fecha_devolucion").value =
        prestamo.fecha_devolucion;
      document.getElementById("editar_estado").value = prestamo.estado;

      new bootstrap.Modal(
        document.getElementById("modalEditarPrestamo")
      ).show();
    } catch (err) {
      console.error(err);
      Swal.fire("Error", "Ocurrió un error en el servidor.", "error");
    }
  });

  // Guardar cambios
  document
    .getElementById("formEditarPrestamo")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      try {
        const res = await fetch(
          `/Biblioteca-2025/controllers/editarPrestamo.php`,
          { method: "POST", body: formData }
        );
        const data = await res.json();

        if (data.success) {
          Swal.fire("Éxito", "Prestamo actualizado.", "success").then(() =>
            location.reload()
          );
        } else {
          Swal.fire("Error", data.message, "error");
        }
      } catch (err) {
        console.error(err);
        Swal.fire("Error", "No se guardo la información.", "error");
      }
    });
});
