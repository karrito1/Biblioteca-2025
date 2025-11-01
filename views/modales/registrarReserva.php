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