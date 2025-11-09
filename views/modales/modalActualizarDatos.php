<!-- MODAL EDITAR USUARIO -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: rgba(32, 29, 29, 0.95); color: white;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalEditarUsuarioLabel">
                    <i class="zmdi zmdi-account-circle"></i> &nbsp; Actualizar mis datos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarUsuario" method="POST">
                <div class="modal-body">
                    <!-- ID oculto -->
                    <input type="hidden" name="id" id="id_usuario" value="<?= $_SESSION['usuario_id'] ?? '' ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electronico</label>
                        <input type="email" class="form-control" id="emailCliente" name="emailCliente" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefonoCliente" name="telefonoCliente">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion</label>
                        <textarea class="form-control" id="direccionCliente" name="direccionCliente" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="passwordd" class="form-label">Nueva contrasena (opcional)</label>
                        <input type="password" class="form-control" id="passworddCliente" name="passworddCliente">
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalEditar = document.getElementById("modalEditarUsuario");

        modalEditar.addEventListener("show.bs.modal", function() {
            fetch("/Biblioteca-2025/controllers/obtenerUsuario.php")
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("nombreCliente").value = data.usuario.nombre;
                        document.getElementById("emailCliente").value = data.usuario.email;
                        document.getElementById("telefonoCliente").value = data.usuario.telefono || "";
                        document.getElementById("direccionCliente").value = data.usuario.direccion || "";
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message,
                            background: "#202020",
                            color: "#fff",
                        });
                    }
                })
                .catch(error => {
                    console.error("Error al obtener usuario:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error al cargar los datos del usuario.",
                        background: "#202020",
                        color: "#fff",
                    });
                });
        });
    });
</script>