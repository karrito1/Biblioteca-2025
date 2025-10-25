<!-- Modal Registrar Préstamo -->
<div class="modal fade" id="modalRegistrarPrestamo" tabindex="-1" aria-labelledby="modalRegistrarPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistrarPrestamoLabel">
                    <i class="zmdi zmdi-assignment-check"></i> Registrar nuevo préstamo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formRegistrarPrestamo" method="POST" action="../controllers/registrar_prestamo.php">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Usuario -->
                        <div class="col-md-6">
                            <label for="usuario_id" class="form-label">Usuario</label>
                            <?php if ($rol === "ADMINISTRADOR") { ?>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php
                                    $usuariosQuery = "SELECT id, nombre FROM usuarios";
                                    $usuariosResult = $baseDatos->efectuarConsulta($usuariosQuery);
                                    while ($usuario = mysqli_fetch_assoc($usuariosResult)) {
                                        echo "<option value='{$usuario['id']}'>" . htmlspecialchars($usuario['nombre']) . "</option>";
                                    }
                                    ?>
                                </select>
                            <?php } else { ?>
                                <!-- Cliente: su propio nombre e ID -->
                                <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['nombre']) ?>" readonly>
                                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
                            <?php } ?>
                        </div>

                        <!-- Libro -->
                        <div class="col-md-6">
                            <label for="libro_id" class="form-label">Libro</label>
                            <select class="form-select" id="libro_id" name="libro_id" required>
                                <option value="">Seleccione un libro</option>
                                <?php
                                $librosQuery = "SELECT id, titulo FROM libros WHERE disponibilidad = 'DISPONIBLE'";
                                $librosResult = $baseDatos->efectuarConsulta($librosQuery);
                                while ($libro = mysqli_fetch_assoc($librosResult)) {
                                    echo "<option value='{$libro['id']}'>" . htmlspecialchars($libro['titulo']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Fecha préstamo -->
                        <div class="col-md-6">
                            <label for="fecha_prestamo" class="form-label">Fecha de préstamo</label>
                            <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" required>
                        </div>

                        <!-- Fecha devolución -->
                        <div class="col-md-6">
                            <label for="fecha_devolucion" class="form-label">Fecha de devolución</label>
                            <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-12">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="ACTIVO">Activo</option>
                                <option value="DEVUELTO">Devuelto</option>
                                <option value="ATRASADO">Atrasado</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Prestamo</button>
                </div>
            </form>
        </div>
    </div>
</div>