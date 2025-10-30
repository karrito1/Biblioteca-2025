<div class="modal fade" id="modalRegistrarPrestamo" tabindex="-1" aria-labelledby="modalPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPrestamoLabel">
                    <i class="zmdi zmdi-assignment"></i> Registrar Prestamo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRegistrarPrestamo" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="libro_id" class="form-label">
                                <i class="zmdi zmdi-book"></i> Libro *
                            </label>
                            <select class="form-select" id="libro_id" name="libro_id" required>
                                <option value="">Seleccione un libro</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="usuario_nombre" class="form-label">
                                <i class="zmdi zmdi-account"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="usuario_nombre"
                                value="<?= htmlspecialchars($_SESSION['nombre'] ?? 'Usuario') ?>" disabled>
                            <input type="hidden" name="usuario_id" value="<?= $_SESSION['id'] ?? '' ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_prestamo" class="form-label">
                                <i class="zmdi zmdi-calendar"></i> Fecha de Prestamo *
                            </label>
                            <input type="date" class="form-control" id="fecha_prestamo"
                                name="fecha_prestamo" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_devolucion" class="form-label">
                                <i class="zmdi zmdi-calendar-check"></i> Fecha de Devolucion *
                            </label>
                            <input type="date" class="form-control" id="fecha_devolucion"
                                name="fecha_devolucion" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        </div>

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
                    <button type="submit" class="btn btn-primary">
                        <i class="zmdi zmdi-check"></i> Registrar Prestamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formPrestamo = document.getElementById('formRegistrarPrestamo');

        if (formPrestamo) {
            cargarLibrosDisponibles();

            const fechaPrestamo = document.getElementById('fecha_prestamo');
            if (fechaPrestamo) {
                fechaPrestamo.addEventListener('change', function() {
                    const fecha = new Date(this.value);
                    const fechaDevolucion = document.getElementById('fecha_devolucion');

                    if (fechaDevolucion) {
                        fecha.setDate(fecha.getDate() + 1);
                        fechaDevolucion.min = fecha.toISOString().split('T')[0];
                    }
                });
            }

            formPrestamo.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('/controllers/registrarPrestamo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Prestamo registrado exitosamente');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud');
                    });
            });
        }

        function cargarLibrosDisponibles() {
            fetch('/Biblioteca-2025/controllers/obtener_libros_disponibles.php')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('libro_id');
                    if (select) {
                        select.innerHTML = '<option value="">Seleccione un libro</option>';

                        data.forEach(libro => {
                            const option = document.createElement('option');
                            option.value = libro.id;
                            option.textContent = `${libro.titulo} - ${libro.autor} (Disponibles: ${libro.disponibilidad})`;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>