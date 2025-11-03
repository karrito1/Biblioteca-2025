<div class="modal fade" id="modalRegistrarReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalReservaLabel">
                    <i class="zmdi zmdi-calendar-check"></i> Registrar Reserva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formRegistrarReserva" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

                    <div class="row">

                        <div class="col-md-12 mb-3">
                            <label for="libro_id_reserva" class="form-label">
                                <i class="zmdi zmdi-book"></i> Libro *
                            </label>
                            <!-- ✅ ID CAMBIADO A libro_id_reserva -->
                            <select class="form-select" id="libro_id_reserva" name="libro_id" required>
                                <option value="">Seleccione un libro</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="zmdi zmdi-account"></i> Usuario
                            </label>
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($_SESSION['email'] ?? 'Usuario') ?>" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_reserva" class="form-label">
                                <i class="zmdi zmdi-calendar"></i> Fecha de Reserva *
                            </label>
                            <input type="date" class="form-control" id="fecha_reserva"
                                name="fecha_reserva" value="<?= date('Y-m-d') ?>" required>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="zmdi zmdi-close"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="zmdi zmdi-check"></i> Guardar Reserva
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>