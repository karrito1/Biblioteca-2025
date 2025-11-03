document.body.addEventListener("click", (e) => {
  if (
    e.target.classList.contains("convertir-prestamo-btn") ||
    e.target.closest(".convertir-prestamo-btn")
  ) {
    const btn = e.target.closest(".convertir-prestamo-btn");
    const reservaId = btn.dataset.id;

    Swal.fire({
      title: "Convertir en prestamo",
      text: "Desea convertir esta reserva en un prestamo?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Si, convertir",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        const formData = new FormData();
        formData.append("reserva_id", reservaId);

        fetch("../controllers/convertirPrestamo.php", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              Swal.fire("Listo", data.message, "success").then(() =>
                location.reload()
              );
            } else {
              Swal.fire("Error", data.message, "error");
            }
          })
          .catch((err) => {
            Swal.fire("Error", "Error al conectar con el servidor", "error");
            console.error(err);
          });
      }
    });
  }
});
