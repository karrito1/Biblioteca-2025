<!-- modal para registrar prestamos -->
<div class="modal fade" id="modalRegistrarPrestamo" tabindex="-1" aria-labelledby="modalRegistrarPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistrarPrestamoLabel">
                    <i class="zmdi zmdi-plus"></i> registrar nuevo prestamo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formRegistrarPrestamo">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">usuario</label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">seleccionar usuario...</option>
                                    <!-- se carga dinamicamente -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="libro_id" class="form-label">libro</label>
                                <select class="form-select" id="libro_id" name="libro_id" required>
                                    <option value="">seleccionar libro...</option>
                                    <!-- se carga dinamicamente -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_devolucion" class="form-label">fecha de devolucion</label>
                                <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">dias de prestamo</label>
                                <div class="btn-group d-flex" role="group">
                                    <input type="radio" class="btn-check" name="dias_prestamo" id="dias7" value="7">
                                    <label class="btn btn-outline-primary" for="dias7">7 dias</label>
                                    
                                    <input type="radio" class="btn-check" name="dias_prestamo" id="dias15" value="15" checked>
                                    <label class="btn btn-outline-primary" for="dias15">15 dias</label>
                                    
                                    <input type="radio" class="btn-check" name="dias_prestamo" id="dias30" value="30">
                                    <label class="btn btn-outline-primary" for="dias30">30 dias</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">observaciones (opcional)</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">cancelar</button>
                    <button type="submit" class="btn btn-primary">registrar prestamo</button>
                </div>
            </form>
        </div>
    </div>
</div>