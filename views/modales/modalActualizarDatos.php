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
                    <input type="hidden" name="id" id="id_usuario" value="<?= $_SESSION['id'] ?? '' ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electronico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="passwordd" class="form-label">Nueva contrasena (opcional)</label>
                        <input type="password" class="form-control" id="passwordd" name="passwordd">
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
    document.addEventListener('DOMContentLoaded', function() {
        const formEditar = document.getElementById('formEditarUsuario');

        // Cargar datos del usuario cuando se abre el modal
        const modalEditar = document.getElementById('modalEditarUsuario');
        modalEditar.addEventListener('show.bs.modal', function() {
            fetch('/Biblioteca-2025/controllers/obtenerUsuario.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('nombre').value = data.usuario.nombre;
                        document.getElementById('email').value = data.usuario.email;
                        document.getElementById('telefono').value = data.usuario.telefono;
                        document.getElementById('direccion').value = data.usuario.direccion;
                    } else {
                        alert('Error al cargar tus datos.');
                    }
                })
                .catch(err => console.error('Error:', err));
        });

        // Guardar cambios
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/Biblioteca-2025/controllers/actualizarUsuario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Tus datos han sido actualizados correctamente.');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error al actualizar tus datos.');
                });
        });
    });
</script>