document.addEventListener("DOMContentLoaded", () => {
  const formPrestamo = document.getElementById("formRegistrarPrestamo");
  const modalPrestamo = document.getElementById("modalRegistrarPrestamo");
  const selectLibro = document.getElementById("libro_id");
  const fechaPrestamo = document.getElementById("fecha_prestamo");
  const fechaDevolucion = document.getElementById("fecha_devolucion");

  // Cargar libros cuando se abre el modal
  if (modalPrestamo) {
    modalPrestamo.addEventListener("show.bs.modal", async () => {
      try {
        selectLibro.innerHTML = "<option>Cargando...</option>";
        const res = await fetch(
          "/Biblioteca-2025/controllers/obtener_libros_disponibles.php"
        );
        const data = await res.json();

        if (data.success && Array.isArray(data.libros)) {
          selectLibro.innerHTML =
            '<option value="">Seleccione un libro</option>';
          data.libros.forEach((l) => {
            const opt = document.createElement("option");
            opt.value = l.id;
            opt.textContent = `${l.titulo} - ${l.autor}`;
            selectLibro.appendChild(opt);
          });
        } else {
          selectLibro.innerHTML = "<option>Error al cargar</option>";
        }
      } catch {
        Swal.fire("Error", "No se pudieron cargar los libros", "error");
      }
    });
  }

  // Calcular fecha mínima de devolución
  if (fechaPrestamo && fechaDevolucion) {
    fechaPrestamo.addEventListener("change", () => {
      const f = new Date(fechaPrestamo.value);
      f.setDate(f.getDate() + 1);
      fechaDevolucion.min = f.toISOString().split("T")[0];
    });
  }

  // Enviar préstamo
  if (formPrestamo) {
    formPrestamo.addEventListener("submit", async (e) => {
      e.preventDefault();
      const datos = new FormData(formPrestamo);

      try {
        const res = await fetch(
          "/Biblioteca-2025/controllers/registrarPrestamo.php",
          {
            method: "POST",
            body: datos,
          }
        );
        const data = await res.json();

        if (data.success) {
          Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: data.message || "Préstamo registrado",
            timer: 1500,
            showConfirmButton: false,
          }).then(() => location.reload());
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo registrar el préstamo",
            "error"
          );
        }
      } catch {
        Swal.fire("Error", "No se pudo conectar con el servidor", "error");
      }
    });
  }
});
