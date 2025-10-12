<?php
require_once("../models/MySQL.php");

// Crear instancia y conectar a la base de datos
$db = new MySQL();
$conexion = $db->conectar();

// Consulta SQL
$query = "SELECT id, nombre, email, telefono, direccion, estado, fecha_registro, Roles FROM usuarios where roles='ADMINISTRADOR'";
$result = $db->efectuarConsulta($query);

// Si la consulta falla, muestra un error
if (!$result) {
    die("<div class='alert alert-danger text-center'>❌ Error en la consulta SQL: " . $db->getError() . "</div>");
}
?>

<!-- === CONTENEDOR DE TABLA DE USUARIOS === -->
<div class="card p-4 mb-5 shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="zmdi zmdi-accounts"></i> Usuarios Registrados</h3>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 text-center shadow-sm">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                <h5 class="card-title">Agregar Libro</h5>
                <a href="controllers/agregarLibros.php" class="btn btn-success w-100 mt-auto">Agregar</a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablausuarios" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['email']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono']) ?></td>
                        <td><?= htmlspecialchars($fila['direccion']) ?></td>
                        <td><?= htmlspecialchars($fila['estado']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                        <td><?= htmlspecialchars($fila['Roles']) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" title="Editar">
                                <i class="zmdi zmdi-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Eliminar">
                                <i class="zmdi zmdi-delete"></i>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$db->desconectar(); // Cierra la conexión al final
?>