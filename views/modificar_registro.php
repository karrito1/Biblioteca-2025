<?php
include_once "../models/MySQL.php";

$mysql = new MySQL();
$mysql->conectar();

$idempleados = $_GET['idempleados'] ?? null;
$fila = null;

if ($idempleados) {
    // Traer datos del empleado
    $queryEmpleado = "SELECT * FROM empleados WHERE idempleados = $idempleados";
    $resultadoEmpleado = $mysql->efectuarConsulta($queryEmpleado);
    $fila = mysqli_fetch_assoc($resultadoEmpleado);
}

// CARGOS
$queryCargos = "SELECT idcargo, nombreCargo FROM cargo";
$resultadoCargos = $mysql->efectuarConsulta($queryCargos);

// DEPARTAMENTOS
$queryDepartamentos = "SELECT iddepartamento, nombreDepartamento FROM departamento";
$resultadoDepartamentos = $mysql->efectuarConsulta($queryDepartamentos);

$mysql->desconectar();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <div class="container-fluid" style="background-color: #73b473ff;">
        <h2 class="text-center py-3">Modificar Empleado</h2>

        <?php if ($fila): ?>
            <form id="formModificar" action="../controller/modificarRegistro.php" method="POST" enctype="multipart/form-data" class="border p-4 rounded bg-white shadow-sm">
                <input type="hidden" name="idempleados" value="<?= $fila['idempleados'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="nombreEmpleado" class="form-control" value="<?= htmlspecialchars($fila['nombreEmpleado']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Número Documento</label>
                        <input type="text" name="numeroDocumento" class="form-control" value="<?= htmlspecialchars($fila['numeroDocumento']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cargo</label>
                        <select name="cargo_idcargo" class="form-select" required>
                            <option value="">Seleccione un cargo</option>
                            <?php
                            mysqli_data_seek($resultadoCargos, 0);
                            while ($cargo = mysqli_fetch_assoc($resultadoCargos)) {
                                $selected = ($cargo['idcargo'] == $fila['cargo_idcargo']) ? 'selected' : '';
                                echo "<option value='{$cargo['idcargo']}' $selected>{$cargo['nombreCargo']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Departamento</label>
                        <div>
                            <?php
                            mysqli_data_seek($resultadoDepartamentos, 0);
                            while ($dpto = mysqli_fetch_assoc($resultadoDepartamentos)) {
                                $checked = ($dpto['iddepartamento'] == $fila['departamento_iddepartamento']) ? 'checked' : '';
                                echo "<div class='form-check form-check-inline'>
                                        <input class='form-check-input' type='radio' name='departamento_iddepartamento' value='{$dpto['iddepartamento']}' $checked required>
                                        <label class='form-check-label'>{$dpto['nombreDepartamento']}</label>
                                      </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha Ingreso</label>
                        <input type="date" name="fechaIngreso" class="form-control" value="<?= $fila['fechaIngreso'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Salario</label>
                        <input type="number" name="salario" class="form-control" value="<?= $fila['salario'] ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($fila['correo']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($fila['telefono']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen actual</label><br>
                    <?php if (!empty($fila['imagen'])): ?>
                        <img src="../<?= htmlspecialchars($fila['imagen']) ?>" width="100" height="100" class="rounded mb-2" style="object-fit: cover;">
                    <?php else: ?>
                        <span>No tiene imagen</span>
                    <?php endif; ?>
                    <input type="file" name="imagen" class="form-control mt-2">
                </div>

                <button type="submit" class="btn btn-success w-25" name="btnmodificarregistro" value="ok">Guardar Cambios</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger text-center">Empleado no encontrado</div>
        <?php endif; ?>
    </div>
</body>

</html>