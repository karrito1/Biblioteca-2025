// Funcion para eliminar prestamo
document.addEventListener("click", function (e) {

    if (e.target.closest(".btnEliminarPrestamo")) {

        let id = e.target.closest(".btnEliminarPrestamo").getAttribute("data-id");

        Swal.fire({
            title: "Eliminar prestamo",
            text: "No se puede deshacer",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {

                let datos = new FormData();
                datos.append("id", id); // Enviamos id

                fetch("../controllers/eliminarPrestamo.php", {
                    method: "POST",
                    body: datos
                })
                .then(r => r.text())
                .then(res => {

                    if (res.trim() === "ok") {
                        Swal.fire("Listo", "Prestamo eliminado", "success")
                        .then(() => location.reload());
                    } else {
                        Swal.fire("Error", "No se elimino", "error");
                    }

                });

            }
        });
    }

});
