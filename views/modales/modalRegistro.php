<div class="modal fade" id="modalRegistrarUsuario" tabindex="-1" aria-labelledby="modalRegistrarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistrarUsuarioLabel">Registrar nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formRegistrarUsuario">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="nombreUsuario" class="form-label">Nombre:</label>
                            <input type="text" class="form-control form-control-sm" id="nombreUsuario" name="nombreUsuario" required>
                        </div>

                      
                        <div class="col-md-6 mb-3">
                            <label for="correoUsuario" class="form-label">Correo:</label>
                            <input type="email" class="form-control form-control-sm" id="correoUsuario" name="correoUsuario" required>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="rolUsuario" class="form-label">Rol:</label>
                            <select id="rolUsuario" name="rolUsuario" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                <option value="ADMINISTRADOR">Administrador</option>
                                <option value="CLIENTE">Cliente</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="claveUsuario" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control form-control-sm" id="claveUsuario" name="claveUsuario" required>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Telefono:</label>
                            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" required>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Direccion:</label>
                            <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" required>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="Estado" class="form-label">Estado:</label>
                            <input type="text" class="form-control form-control-sm" id="estado" name="estado" required>
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="fechaCreacion" class="form-label">Direccion:</label>
                            <input type="date" class="form-control form-control-sm" id="fechaRegistro" name="fechaRegistro" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>