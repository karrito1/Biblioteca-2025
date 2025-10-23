<div class="modal fade" id="modalRegistrarUsuario" tabindex="-1" aria-labelledby="modalRegistrarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistrarUsuarioLabel">Registrar nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form method="POST" id="formRegistro" action="/Biblioteca-2025/controllers/agregarUsuarios.php">


                    <!-- DATOS PERSONALES -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo electrónico:</label>
                            <input type="email" class="form-control form-control-sm" id="email" name="email" required>
                        </div>
                    </div>

                    <!-- DATOS DE CUENTA -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="Roles" class="form-label">Rol:</label>
                            <select id="Roles" name="Roles" class="form-select form-select-sm" required>
                                <option value="">Seleccione...</option>
                                <option value="ADMINISTRADOR">Administrador</option>
                                <option value="CLIENTE">Cliente</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="passwordd" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control form-control-sm" id="passwordd" name="passwordd" required>
                        </div>
                    </div>

                    <!-- CONTACTO -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" required>
                        </div>
                    </div>

                    <!-- OTROS DATOS -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado:</label>
                            <input type="text" class="form-control form-control-sm" id="estado" name="estado" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="fecha_registro" class="form-label">Fecha de registro:</label>
                            <input type="date" class="form-control form-control-sm" id="fecha_registro" name="fecha_registro" required>
                        </div>
                    </div>

                    <!-- BOTÓN -->
                    <button type="submit" class="btn btn-success w-100" name="btn_registrar" value="ok">
                        Registrar
                    </button>
                </form>


            </div>
        </div>
    </div>
</div>
