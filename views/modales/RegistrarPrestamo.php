<!-- Modal para registrar un prestamo -->
<div class="modal fade" id="modalRegistrarPrestamo" tabindex="-1" aria-labelledby="modalRegistrarPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Encabezado del modal -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistrarPrestamoLabel">
                    <i class="zmdi zmdi-assignment-check"></i> Registrar Prestamo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">
                <!-- Formulario para registrar prestamo -->
                <form id="formRegistrarPrestamo"  method="POST" >
                    <div class="row g-3">

                        <!-- Seleccion de usuario -->
                        <div class="col-md-6">
                            <label for="usuario_id" class="form-label">Usuario</label>
                            <select id="usuario_id" name="usuario_id" class="form-select" required>
                                <option value="">Seleccione un usuario</option>
                                <?php
                                // Consultar usuarios disponibles
                                $usuariosQuery = "SELECT id, nombre FROM usuarios";
                                $usuariosResult = $baseDatos->efectuarConsulta($usuariosQuery);
                                while ($u = mysqli_fetch_assoc($usuariosResult)) {
                                    echo "<option value='{$u['id']}'>{$u['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Seleccion de libro -->
                        <div class="col-md-6">
                            <label for="libro_id" class="form-label">Libro</label>
                            <select id="libro_id" name="libro_id" class="form-select" required>
                                <option value="">Seleccione un libro</option>
                                <?php
                                // Consultar libros disponibles
                                $librosQuery = "SELECT id, isbn FROM libros WHERE estado = 'disponible'";
                                $librosResult = $baseDatos->efectuarConsulta($librosQuery);
                                while ($l = mysqli_fetch_assoc($librosResult)) {
                                    echo "<option value='{$l['id']}'>{$l['isbn']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Fecha de prestamo -->
                        <div class="col-md-6">
                            <label for="fecha_prestamo" class="form-label">Fecha Prestamo</label>
                            <input type="date" id="fecha_prestamo" name="fecha_prestamo" class="form-control" required>
                        </div>

                        <!-- Fecha de devolucion -->
                        <div class="col-md-6">
                            <label for="fecha_devolucion" class="form-label">Fecha Devolucion</label>
                            <input type="date" id="fecha_devolucion" name="fecha_devolucion" class="form-control" required>
                        </div>

                        <!-- Estado del prestamo -->
                        <div class="col-md-12">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="devuelto">Devuelto</option>
                                <option value="retrasado">Retrasado</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Pie del modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formRegistrarPrestamo" class="btn btn-primary">
                    <i class="zmdi zmdi-save"></i> Guardar Prestamo
                </button>
            </div>
        </div>
    </div>
</div>