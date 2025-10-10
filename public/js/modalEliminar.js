let modalEliminar = document.getElementById("modalEliminar");

// Pasar datos al modal
modalEliminar.addEventListener("show.bs.modal", function (event) {
  let button = event.relatedTarget;
  let nombre = button.getAttribute("data-nombre");
  let documento = button.getAttribute("data-documento");

  document.getElementById("nombreEliminar").value = nombre;
  document.getElementById("documentoEliminar").value = documento;
  document.getElementById("nombreMostrar").textContent = nombre;
});

document
  .getElementById("formEliminar")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("../controller/eliminar_registro.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.carepingo === "estovapasar") {
          Swal.fire({
            icon: "success",
            title: "Empleado Inactivado",
            text: data.message,
            timer: 2000,
            showConfirmButton: false,
          });

          let documento = formData.get("numeroDocumento");
          let row = document.querySelector(
            `tr[data-documento='${documento}'] td:nth-child(10)`
          );
          if (row) row.textContent = "INACTIVO";

          let modal = bootstrap.Modal.getInstance(modalEliminar);
          modal.hide();
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
  });
