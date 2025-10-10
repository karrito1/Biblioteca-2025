document.querySelector("#formRegistro")
  ?.addEventListener("submit", function (e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);
    formData.append("btn_registrar", "ok");

    Swal.fire({
      title: "¿Confirmar registro?",
      text: "Se guardará un nuevo usuario en el sistema",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Si, registrar",
      cancelButtonText: "Cancelar el proceso",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) {
        fetch("../controller/registrousuarios.php", {
          method: "POST",
          body: formData,
        })
          .then((res) => res.json())
          .then((data) => {
            if (data.status === "success") {
              Swal.fire({
                icon: "success",
                title: "¡Registrado!",
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
          .catch((errorrr) => {
            console.error("Error en la petición:", errorrr);
            Swal.fire({
              icon: "error",
              title: "Error inesperado",
              text: "No se pudo conectar con el servidor",
            });
          });
      }
    });
  });
