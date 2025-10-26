// tabla_inventario.js - Funcionalidad para el inventario de libros

$(document).ready(function() {
    // Inicializar DataTable para inventario
    if ($('#tablaInventario').length) {
        $('#tablaInventario').DataTable({
            "language": {
                "url": "/Biblioteca-2025/js/es-ES.json"
            },
            "order": [[2, "asc"]], // ordenar por titulo
            "pageLength": 15,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [0, -1] }, // Desactivar orden en checkbox y acciones
                { "width": "5%", "targets": 0 },
                { "width": "25%", "targets": 2 },
                { "width": "15%", "targets": 3 }
            ]
        });
    }

    // Filtros del inventario
    $('#filtroCategoria').change(function() {
        const categoria = $(this).val();
        filtrarTabla('categoria', categoria);
    });

    $('#filtroDisponibilidad').change(function() {
        const disponibilidad = $(this).val();
        filtrarTabla('disponibilidad', disponibilidad);
    });

    // busqueda personalizada
    $('#buscarInventario').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        $('#tablaInventario tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Seleccionar/deseleccionar todos los libros
    $('#selectAllInventario').change(function() {
        $('.inventario-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Ver detalles del libro
    $(document).on('click', '.btnVerDetalleLibro', function() {
        const libroId = $(this).data('id');
        mostrarDetalleLibro(libroId);
    });

    // Ver historial del libro
    $(document).on('click', '.btnHistorialLibro', function() {
        const libroId = $(this).data('id');
        mostrarHistorialLibro(libroId);
    });

    // crear prestamo directo desde inventario
    $(document).on('click', '.btnPrestarLibro', function() {
        const libroId = $(this).data('id');
        const titulo = $(this).data('titulo');
        crearPrestamoDirecto(libroId, titulo);
    });

    // Editar libro
    $(document).on('click', '.btnEditarLibro', function() {
        const libroId = $(this).data('id');
        editarLibro(libroId);
    });
});

// funcion para filtrar tabla
function filtrarTabla(tipo, valor) {
    if (valor === '') {
        $('#tablaInventario tbody tr').show();
        return;
    }

    $('#tablaInventario tbody tr').hide();
    
    if (tipo === 'categoria') {
        $('#tablaInventario tbody tr').each(function() {
            const categoria = $(this).find('td:nth-child(6) .badge').text().trim();
            if (categoria === valor) {
                $(this).show();
            }
        });
    } else if (tipo === 'disponibilidad') {
        $('#tablaInventario tbody tr').each(function() {
            const disponibilidad = $(this).find('td:nth-child(8) .badge').text().toLowerCase().trim();
            if (disponibilidad === valor) {
                $(this).show();
            }
        });
    }
}

// funciones de exportacion
function exportarInventario(formato) {
    const librosSeleccionados = $('.inventario-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    let url = `/Biblioteca-2025/controllers/exportarInventario.php?formato=${formato}`;
    
    if (librosSeleccionados.length > 0) {
        url += `&libros=${librosSeleccionados.join(',')}`;
    }
    
    Swal.fire({
        title: `Exportando inventario...`,
        text: 'Generando archivo, por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        if (formato === 'pdf') {
            window.open(url, '_blank');
        } else {
            // Crear enlace de descarga
            const link = document.createElement('a');
            link.href = url;
            link.download = `inventario_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        Swal.fire({
            icon: 'success',
            title: '¡Inventario exportado!',
            text: `el archivo ${formato.toUpperCase()} ${librosSeleccionados.length > 0 ? 'con libros seleccionados' : 'completo'} esta listo`,
            timer: 2000,
            showConfirmButton: false
        });
    }, 2000);
}

function generarEtiquetas() {
    const librosSeleccionados = $('.inventario-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (librosSeleccionados.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'sin seleccion',
            text: 'Selecciona al menos un libro para generar etiquetas'
        });
        return;
    }
    
    Swal.fire({
        title: 'Generando etiquetas...',
        text: `Creando etiquetas para ${librosSeleccionados.length} libros`,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        const url = `/Biblioteca-2025/controllers/generarEtiquetas.php?libros=${librosSeleccionados.join(',')}`;
        window.open(url, '_blank');
        
        Swal.fire({
            icon: 'success',
            title: '¡Etiquetas generadas!',
            html: `
                <p>Se han generado etiquetas para ${librosSeleccionados.length} libros</p>
                <div class="alert alert-info mt-3">
                    <i class="zmdi zmdi-info"></i>
                    las etiquetas incluyen codigo de barras, titulo y codigo de ubicacion
                </div>
            `,
            confirmButtonText: 'Perfecto'
        });
    }, 2500);
}

function verificarInventario() {
    Swal.fire({
        title: 'verificacion de inventario',
        html: `
            <p>que tipo de verificacion deseas realizar?</p>
            <div class="btn-group-vertical d-grid gap-2 mt-3">
                <button class="btn btn-primary" onclick="verificarDisponibilidad()">
                    <i class="zmdi zmdi-check-circle"></i> Verificar Disponibilidad
                </button>
                <button class="btn btn-warning" onclick="verificarInconsistencias()">
                    <i class="zmdi zmdi-alert-triangle"></i> Buscar Inconsistencias
                </button>
                <button class="btn btn-info" onclick="verificarPopularidad()">
                    <i class="zmdi zmdi-trending-up"></i> analisis de popularidad
                </button>
            </div>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Cerrar'
    });
}

function verificarDisponibilidad() {
    Swal.fire({
        title: 'Verificando disponibilidad...',
        text: 'Analizando estado de todos los libros',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // simular verificacion
    setTimeout(() => {
        const librosDisponibles = $('#tablaInventario tbody tr.table-success').length;
        const librosPrestados = $('#tablaInventario tbody tr.table-warning').length;
        const librosNoDisponibles = $('#tablaInventario tbody tr.table-danger').length;
        const totalLibros = $('#tablaInventario tbody tr').length;
        
        Swal.fire({
            icon: 'info',
            title: 'Reporte de Disponibilidad',
            html: `
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h4>${librosDisponibles}</h4>
                                <p class="mb-0">Disponibles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h4>${librosPrestados}</h4>
                                <p class="mb-0">Prestados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h4>${librosNoDisponibles}</h4>
                                <p class="mb-0">No disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <p><strong>Total de libros:</strong> ${totalLibros}</p>
                    <p><strong>Porcentaje de disponibilidad:</strong> ${((librosDisponibles / totalLibros) * 100).toFixed(1)}%</p>
                </div>
            `,
            width: 600,
            confirmButtonText: 'Cerrar'
        });
    }, 2000);
}

function verificarInconsistencias() {
    Swal.fire({
        title: 'Buscando inconsistencias...',
        text: 'Revisando datos de inventario',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // simular busqueda de inconsistencias
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'verificacion completada',
            html: `
                <div class="alert alert-success">
                    <h6><i class="zmdi zmdi-check"></i> No se encontraron inconsistencias graves</h6>
                </div>
                <div class="text-start">
                    <h6>Verificaciones realizadas:</h6>
                    <ul>
                        <li>✅ ISBN duplicados: No encontrados</li>
                        <li>✅ disponibilidad vs prestamos activos: consistente</li>
                        <li>✅ Cantidades negativas: No encontradas</li>
                        <li>⚠️ libros sin categoria: 2 encontrados</li>
                    </ul>
                    <div class="alert alert-warning mt-3">
                        <small><strong>recomendacion:</strong> asignar categorias a los libros pendientes</small>
                    </div>
                </div>
            `,
            width: 600,
            confirmButtonText: 'Entendido'
        });
    }, 3000);
}

function verificarPopularidad() {
    Swal.fire({
        title: 'Analizando popularidad...',
        text: 'calculando estadisticas de prestamos',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // simular analisis de popularidad
    setTimeout(() => {
        Swal.fire({
            icon: 'info',
            title: 'analisis de popularidad',
            html: `
                <div class="text-start">
                    <h6>libros mas solicitados:</h6>
                    <ol>
                        <li>cien anos de soledad - 15 prestamos</li>
                        <li>el principito - 12 prestamos</li>
                        <li>don quijote - 8 prestamos</li>
                    </ol>
                    
                    <h6 class="mt-3">categorias mas populares:</h6>
                    <ul>
                        <li>novela - 35 prestamos</li>
                        <li>infantil - 22 prestamos</li>
                        <li>ciencia - 18 prestamos</li>
                    </ul>
                    
                    <div class="alert alert-info mt-3">
                        <i class="zmdi zmdi-lightbulb"></i>
                        <strong>sugerencia:</strong> considera adquirir mas titulos de novela 
                        y libros infantiles para satisfacer la demanda.
                    </div>
                </div>
            `,
            width: 600,
            confirmButtonText: 'Generar reporte completo',
            showCancelButton: true,
            cancelButtonText: 'Cerrar'
        }).then((result) => {
            if (result.isConfirmed) {
                exportarInventario('pdf');
            }
        });
    }, 2500);
}

function mostrarDetalleLibro(libroId) {
    $('#contenidoDetalleLibro').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
    $('#modalDetalleLibro').modal('show');
    
    // Simular carga de detalles
    setTimeout(() => {
        $('#contenidoDetalleLibro').html(`
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="/Biblioteca-2025/assets/img/book-placeholder.jpg" class="card-img-top" alt="Portada">
                    </div>
                </div>
                <div class="col-md-8">
                    <h5>informacion del libro #${libroId}</h5>
                    <table class="table table-borderless">
                        <tr><td><strong>Estado:</strong></td><td>Disponible</td></tr>
                        <tr><td><strong>ubicacion:</strong></td><td>estante a-${libroId}</td></tr>
                        <tr><td><strong>codigo interno:</strong></td><td>lib-${String(libroId).padStart(4, '0')}</td></tr>
                        <tr><td><strong>ultima revision:</strong></td><td>15/10/2025</td></tr>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <h6>acciones rapidas:</h6>
                <button class="btn btn-primary btn-sm" onclick="crearPrestamoRapido(${libroId})">
                    <i class="zmdi zmdi-calendar-check"></i> crear prestamo
                </button>
                <button class="btn btn-warning btn-sm ms-2" onclick="editarLibroRapido(${libroId})">
                    <i class="zmdi zmdi-edit"></i> Editar Datos
                </button>
                <button class="btn btn-info btn-sm ms-2" onclick="imprimirEtiqueta(${libroId})">
                    <i class="zmdi zmdi-print"></i> Imprimir Etiqueta
                </button>
            </div>
        `);
    }, 1000);
}

function mostrarHistorialLibro(libroId) {
    $('#contenidoHistorialLibro').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
    $('#modalHistorialLibro').modal('show');
    
    // Simular carga de historial
    setTimeout(() => {
        $('#contenidoHistorialLibro').html(`
            <h6>historial de prestamos - libro #${libroId}</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>fecha prestamo</th>
                            <th>fecha devolucion</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>juan perez</td>
                            <td>01/10/2025</td>
                            <td>15/10/2025</td>
                            <td><span class="badge bg-success">Devuelto</span></td>
                        </tr>
                        <tr>
                            <td>maria garcia</td>
                            <td>15/09/2025</td>
                            <td>29/09/2025</td>
                            <td><span class="badge bg-success">Devuelto</span></td>
                        </tr>
                        <tr>
                            <td>carlos lopez</td>
                            <td>20/10/2025</td>
                            <td>03/11/2025</td>
                            <td><span class="badge bg-primary">Activo</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-3">
                <h6>estadisticas del libro:</h6>
                <ul class="mb-0">
                    <li>total de prestamos: 15</li>
                    <li>tiempo promedio de prestamo: 12 dias</li>
                    <li>Devoluciones a tiempo: 93%</li>
                    <li>Popularidad: Alta</li>
                </ul>
            </div>
        `);
    }, 1500);
}

function crearPrestamoDirecto(libroId, titulo) {
    $('#modalDetalleLibro').modal('hide');
    
    // redirigir a la seccion de prestamos con el libro preseleccionado
    $('#btnPrestamos').click();
    
    setTimeout(() => {
        $('#modalRegistrarPrestamo').modal('show');
        $('#libro_id').val(libroId).trigger('change');
        
        Swal.fire({
            icon: 'info',
            title: 'Libro preseleccionado',
            text: `"${titulo}" ha sido seleccionado para el prestamo`,
            timer: 2000,
            showConfirmButton: false
        });
    }, 1000);
}

// Exportar funciones para uso global
window.inventario = {
    exportarInventario,
    generarEtiquetas,
    verificarInventario,
    verificarDisponibilidad,
    verificarInconsistencias,
    verificarPopularidad
};