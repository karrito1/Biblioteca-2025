document.addEventListener("DOMContentLoaded", () => {
  const modalReserva = document.getElementById("modalRegistrarReserva");
  const selectLibro = document.getElementById("libro_id_reserva");
  const formReserva = document.getElementById("formRegistrarReserva");

  function cargarLibros() {
    if (!selectLibro) {
      console.error("No se encontro el select");
      return;
    }

    selectLibro.innerHTML = "<option>Cargando...</option>";

    fetch("/Biblioteca-2025/controllers/obtener_libros_disponibles.php")
      .then((r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then((data) => {
        if (!data.libros || data.libros.length === 0) {
          selectLibro.innerHTML =
            '<option value="">No hay libros disponibles</option>';
          return;
        }

        selectLibro.innerHTML = '<option value="">Seleccionar libro</option>';
        data.libros.forEach((libro) => {
          const option = document.createElement("option");
          option.value = libro.id;
          option.textContent = libro.titulo;
          selectLibro.appendChild(option);
        });
      })
      .catch((error) => {
        console.error("Error:", error);
        selectLibro.innerHTML = '<option value="">Error al cargar</option>';
      });
  }

  if (modalReserva) {
    modalReserva.addEventListener("show.bs.modal", cargarLibros);
  }

  if (formReserva) {
    formReserva.addEventListener("submit", (e) => {
      e.preventDefault();
      const formData = new FormData(formReserva);

      fetch("/Biblioteca-2025/controllers/registrarReserva.php", {
        method: "POST",
        body: formData,
      })
        .then((r) => r.json())
        .then((data) => {
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Exito",
              text: "Reserva guardada",
              timer: 1500,
              showConfirmButton: false,
            }).then(() => location.reload());
          } else {
            Swal.fire("Error", data.message || "No se pudo guardar", "error");
          }
        })
        .catch(() => {
          Swal.fire("Error", "Error al conectar", "error");
        });
    });
  }
});
