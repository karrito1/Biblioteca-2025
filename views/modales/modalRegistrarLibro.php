<div class="modal fade" id="modalRegistrarLibro" tabindex="-1" aria-labelledby="modalRegistrarLibroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formRegistrarLibro" action="/controllers/agregarLibros.php" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalRegistrarLibroLabel"><i class="zmdi zmdi-book-add"></i> Registrar Libro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
<<<<<<< HEAD
                        <label for="titulo" class="form-label">titulo</label>
=======
                        <label for="titulo" class="form-label">Título</label>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="autor" class="form-label">Autor</label>
                        <input type="text" class="form-control" id="autor" name="autor" required>
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                    </div>
                    <div class="mb-3">
<<<<<<< HEAD
                        <label for="categoria" class="form-label">categoria</label>
=======
                        <label for="categoria" class="form-label">Categoría</label>
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="disponibilidad" class="form-label">Disponibilidad</label>
                        <select class="form-select" id="disponibilidad" name="disponibilidad" required>
                            <option value="Disponible">Disponible</option>
                            <option value="No disponible">No disponible</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Registrar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>
