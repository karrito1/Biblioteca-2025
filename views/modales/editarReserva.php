<div class="modal fade" id="modalEditarReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="zmdi zmdi-edit"></i> Editar Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarReserva">
                    <input type="hidden" id="reserva_id" name="reserva_id">

                    <div class="mb-3">
                        <label for="edit_fecha_reserva" class="form-label">Fecha de Reserva</label>
                        <input type="date" class="form-control" id="edit_fecha_reserva" name="fecha_reserva" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">Estado</label>
                        <select class="form-select" id="edit_estado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="zmdi zmdi-save"></i> Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalEditarReserva = new bootstrap.Modal(document.getElementById("modalEditarReserva"));
        const formEditarReserva = document.getElementById("formEditarReserva");

        document.body.addEventListener("click", (e) => {
            if (e.target.classList.contains("editar-reserva-btn") || e.target.closest(".editar-reserva-btn")) {

                const btn = e.target.classList.contains("editar-reserva-btn") ? e.target : e.target.closest(".editar-reserva-btn");
                const reservaId = btn.dataset.id;
                const originalContent = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                fetch(`../controllers/obtener_reserva.php?id=${reservaId}`)
                    .then(res => res.text())
                    .then(text => {
                        if (!text) throw new Error("Respuesta vacía");
                        const data = JSON.parse(text);

                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            return;
                        }

                        document.getElementById("reserva_id").value = data.id;
                        document.getElementById("edit_fecha_reserva").value = data.fecha_reserva;
                        document.getElementById("edit_estado").value = data.estado.toLowerCase();

                        modalEditarReserva.show();
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Error al cargar la reserva', 'error');
                        console.error(err);
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    });
            }
        });

        formEditarReserva.addEventListener("submit", (e) => {
            e.preventDefault();

            const submitBtn = formEditarReserva.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            const formData = new FormData(formEditarReserva);

            fetch("../controllers/actualizarReserva.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(text => {
                    const res = JSON.parse(text);

                    if (res.success) {
                        modalEditarReserva.hide();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Actualizado!',
                            text: 'Reserva actualizada exitosamente',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Error al actualizar la reserva', 'error');
                    console.error(err);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                });
        });
    });
</script>