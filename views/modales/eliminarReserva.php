<div class="modal fade" id="modalEliminarReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="zmdi zmdi-delete"></i> Eliminar Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Esta seguro que desea eliminar esta reserva?</p>
                <input type="hidden" id="eliminar_reserva_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                    <i class="zmdi zmdi-delete"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>