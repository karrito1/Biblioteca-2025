<!-- Modal Editar Prestamo -->
<div class="modal fade" id="modalEditarPrestamo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Préstamo</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarPrestamo">
                <div class="modal-body">

                    <input type="hidden" id="editar_prestamo_id" name="editar_prestamo_id">

                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <select id="editar_usuario_id" name="editar_usuario_id" class="form-control" required></select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Libro (ISBN)</label>
                        <select id="editar_libro_id" name="editar_libro_id" class="form-control" required></select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha Prestamo</label>
                        <input type="date" id="editar_fecha_prestamo" name="editar_fecha_prestamo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha Devolución</label>
                        <input type="date" id="editar_fecha_devolucion" name="editar_fecha_devolucion" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select id="editar_estado" name="editar_estado" class="form-control" required>
                            <option value="activo">Activo</option>
                            <option value="devuelto">Devuelto</option>
                            <option value="retrasado">Retrasado</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </form>

        </div>
    </div>
</div>