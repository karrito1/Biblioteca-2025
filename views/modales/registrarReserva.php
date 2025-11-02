<!-- MODAL REGISTRAR RESERVA -->
<div class="modal fade" id="formRegistrarReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalReservaLabel">
                    <i class="zmdi zmdi-calendar-note"></i> Registrar Reserva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formRegistrarReserva" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <!-- Libro -->
                        <div class="col-md-12 mb-3">
                            <label for="libro_id" class="form-label">
                                <i class="zmdi zmdi-book"></i> Libro *
                            </label>
                            <select class="form-select" id="libro_id" name="libro_id" required>
                                <option value="">Seleccione un libro</option>
                            </select>
                        </div>

                        <!-- Usuario -->
                        <div class="col-md-6 mb-3">
                            <label for="usuario_nombre" class="form-label">
                                <i class="zmdi zmdi-account"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="usuario_nombre"
                                value="<?= htmlspecialchars($_SESSION['email'] ?? 'Usuario') ?>" disabled>
                            <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id'] ?? '' ?>">
                        </div>

                        <!-- Fecha de reserva -->
                        <div class="col-md-6 mb-3">
                            <label for="fecha_reserva" class="form-label">
                                <i class="zmdi zmdi-calendar"></i> Fecha de Reserva *
                            </label>
                            <input type="date" class="form-control" id="fecha_reserva"
                                name="fecha_reserva" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <!-- Fecha de expiración -->
                        <div class="col-md-6 mb-3">
                            <label for="fecha_expiracion" class="form-label">
                                <i class="zmdi zmdi-timer-off"></i> Fecha de Expiración *
                            </label>
                            <input type="date" class="form-control" id="fecha_expiracion"
                                name="fecha_expiracion" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>

                        <!-- Observaciones -->
                        <div class="col-md-12 mb-3">
                            <label for="observaciones" class="form-label">
                                <i class="zmdi zmdi-comment-text"></i> Observaciones
                            </label>
                            <textarea class="form-control" id="observaciones" name="observaciones"
                                rows="3" placeholder="Observaciones opcionales..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="zmdi zmdi-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="zmdi zmdi-check"></i> Registrar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formReserva = document.getElementById('formRegistrarReserva');
        const selectLibro = document.getElementById('libro_id');

        // Cargar libros cuando se abre el modal
        const modalReserva = document.getElementById('formRegistrarReserva');
        modalReserva.addEventListener('show.bs.modal', function() {
            selectLibro.innerHTML = '<option value="">Cargando libros...</option>';

            fetch('/Biblioteca-2025/controllers/obtener_libros_disponibles.php')
                .then(res => res.json())
                .then(data => {
                    selectLibro.innerHTML = '<option value="">Seleccione un libro</option>';
                    if (data.success && Array.isArray(data.libros)) {
                        data.libros.forEach(libro => {
                            const option = document.createElement('option');
                            option.value = libro.id;
                            option.textContent = `${libro.titulo} - ${libro.autor}`;
                            selectLibro.appendChild(option);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar los libros'
                        });
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    selectLibro.innerHTML = '<option value="">Error al cargar</option>';
                });
        });

        // Enviar formulario
        formReserva.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/Biblioteca-2025/controllers/registrarReserva.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Exito',
                            text: data.message || 'Reserva registrada correctamente',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo registrar la reserva'
                        });
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud'
                    });
                });
        });
    });
</script>