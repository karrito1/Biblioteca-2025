document.addEventListener("DOMContentLoaded", () => {
  const btnCerrarSesion = document.getElementById("btnConfirmarCerrarSesion");

  if (!btnCerrarSesion) return;

  btnCerrarSesion.addEventListener("click", () => {
    fetch("/Biblioteca-2025/controllers/cerrarSesion.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Sesión cerrada",
            text: "Serás redirigido al inicio de sesión.",
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            // Ocultar el modal antes de redirigir
            const modalEl = document.getElementById("modalCerrarSesion");
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Redirigir al login
            window.location.href = "/Biblioteca-2025/index.php";
          });
        } else {
          Swal.fire(
            "Error",
            data.message || "No se pudo cerrar la sesión.",
            "error"
          );
        }
      })
      .catch((err) => {
        console.error("Error:", err);
        Swal.fire("Error", "No se pudo contactar con el servidor.", "error");
      });
  });
});
