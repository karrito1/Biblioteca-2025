document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formRegistrarLibro"); // ID del formulario de libros
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    formData.append("btn_registrar", "ok");

<<<<<<< HEAD
    // confirmacion antes de registrar
    Swal.fire({
      title: "¿Confirmar registro?",
      text: "se guardara un nuevo libro en el sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "si, registrar",
=======
    // 💬 Confirmación antes de registrar
    Swal.fire({
      title: "¿Confirmar registro?",
      text: "Se guardará un nuevo libro en el sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, registrar",
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      reverseButtons: true,
    }).then((result) => {
      if (!result.isConfirmed) return;

      fetch("/Biblioteca-2025/controllers/agregarLibros.php", { // URL del controlador de libros
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              icon: "success",
              title: "¡Libro registrado!",
              text: data.message,
              showConfirmButton: false,
              timer: 1800,
              background: "#f0f9f0",
              color: "#155724",
            });

            form.reset();
            const modalElement = document.getElementById("modalRegistrarLibro"); // ID del modal de libros
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();

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
<<<<<<< HEAD
          console.error("error en la peticion:", error);
=======
          console.error("Error en la petición:", error);
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
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
