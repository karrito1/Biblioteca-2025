
document.addEventListener("DOMContentLoaded", () => {

    const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");

    // Capturar click en cualquier boton eliminar
    document.body.addEventListener("click", (e) => {
        if(e.target.classList.contains("eliminar-reserva-btn") || e.target.closest(".eliminar-reserva-btn")){

            const btn = e.target.closest(".eliminar-reserva-btn");
            const reservaId = btn.dataset.id;

            if(!reservaId){
                Swal.fire('Error', 'No se encontro el ID de la reserva', 'error');
                return;
            }

            // Guardar ID en el hidden
            document.getElementById("eliminar_reserva_id").value = reservaId;

            // Mostrar confirmacion con SweetAlert
            Swal.fire({
                title: 'Eliminar reserva',
                text: "Esta seguro que desea eliminar esta reserva?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if(result.isConfirmed){
                    // Crear FormData
                    const formData = new FormData();
                    formData.append('reserva_id', reservaId);

                    fetch('../controllers/eliminarReserva.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            Swal.fire('Eliminado!', data.message, 'success').then(()=> location.reload());
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Error al conectar con el servidor', 'error');
                        console.error(err);
                    });
                }
            });
        }
    });
});

