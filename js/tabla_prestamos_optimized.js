$(document).ready(function() {
    // Initialize DataTables
    const dtConfig = {
        language: { url: "../js/es-ES.json" },
        pageLength: 10,
        responsive: true,
        columnDefs: [{ orderable: false, targets: -1 }]
    };
    
    if ($('#tablaPrestamos').length) {
        $('#tablaPrestamos').DataTable({...dtConfig, order: [[0, "desc"]]});
    }
    
    if ($('#tablaMisPrestamos').length) {
        $('#tablaMisPrestamos').DataTable({...dtConfig, order: [[3, "desc"]]});
    }

    // Date configuration
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    
    $('#fecha_devolucion, #nueva_fecha_devolucion, #nueva_fecha_devolucion_cliente').attr('min', minDate);

    // Date handlers
    $('input[name="dias_prestamo"]').change(function() {
        const date = new Date();
        date.setDate(date.getDate() + parseInt($(this).val()));
        $('#fecha_devolucion').val(date.toISOString().split('T')[0]);
    });

    $('input[name="extender_dias"], input[name="extender_dias_cliente"]').change(function() {
        const date = new Date();
        date.setDate(date.getDate() + parseInt($(this).val()));
        const target = $(this).attr('name') === 'extender_dias' ? '#nueva_fecha_devolucion' : '#nueva_fecha_devolucion_cliente';
        $(target).val(date.toISOString().split('T')[0]);
    });

    // Set default date (15 days)
    if ($('#fecha_devolucion').length) {
        const defaultDate = new Date();
        defaultDate.setDate(defaultDate.getDate() + 15);
        $('#fecha_devolucion').val(defaultDate.toISOString().split('T')[0]);
    }

    // Filters
    $('#filtroEstado, #filtroEstadoCliente').change(function() {
        const estado = $(this).val();
        const tabla = $(this).attr('id') === 'filtroEstado' ? '#tablaPrestamos' : '#tablaMisPrestamos';
        
        if (!estado) {
            $(tabla + ' tbody tr').show();
            return;
        }
        
        $(tabla + ' tbody tr').hide();
        if (estado === 'vencido') {
            $(tabla + ' tbody tr.table-danger').show();
        } else {
            $(tabla + ' tbody tr').each(function() {
                const estadoFila = $(this).find('.badge').text().toLowerCase().trim();
                if (estadoFila === estado || $(this).data('estado') === estado) {
                    $(this).show();
                }
            });
        }
    });

    // Search
    $('#buscarPrestamo, #buscarMiPrestamo').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        const tabla = $(this).attr('id') === 'buscarPrestamo' ? '#tablaPrestamos' : '#tablaMisPrestamos';
        $(tabla + ' tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    // Register loan
    $('#formRegistrarPrestamo').submit(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '¿registrar prestamo?',
            text: 'se registrara el nuevo prestamo y se actualizara la disponibilidad del libro',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controllers/agregarPrestamos.php',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: response.exito ? 'success' : 'error',
                            title: response.exito ? 'exito!' : 'error',
                            text: response.mensaje,
                            timer: response.exito ? 2000 : null,
                            showConfirmButton: !response.exito
                        }).then(() => {
                            if (response.exito) {
                                $('#modalRegistrarPrestamo').modal('hide');
                                location.reload();
                            }
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error del sistema',
                            text: 'ocurrio un error al procesar la solicitud'
                        });
                    }
                });
            }
        });
    });

    // Return book
    $(document).on('click', '.btnDevolverLibro', function() {
        const prestamoId = $(this).data('id');
        
        Swal.fire({
            title: '¿confirmar devolucion?',
            text: 'el libro sera marcado como devuelto y estara disponible nuevamente',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si, devolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controllers/devolverPrestamo.php',
                    type: 'POST',
                    data: { prestamo_id: prestamoId },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: response.exito ? 'success' : 'error',
                            title: response.exito ? '¡Libro devuelto!' : 'Error',
                            text: response.mensaje,
                            timer: response.exito ? 2000 : null,
                            showConfirmButton: !response.exito
                        }).then(() => {
                            if (response.exito) location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error del sistema',
                            text: 'no se pudo procesar la devolucion'
                        });
                    }
                });
            }
        });
    });

    // Renew loan
    $(document).on('click', '.btnRenovarPrestamo', function() {
        const prestamoId = $(this).data('id');
        const fechaActual = $(this).data('fecha');
        
        $('#prestamo_id_renovar').val(prestamoId);
        
        const fecha = new Date(fechaActual);
        fecha.setDate(fecha.getDate() + 1);
        $('#nueva_fecha_devolucion').attr('min', fecha.toISOString().split('T')[0]);
        
        fecha.setDate(fecha.getDate() + 15);
        $('#nueva_fecha_devolucion').val(fecha.toISOString().split('T')[0]);
        
        $('#modalRenovarPrestamo').modal('show');
    });

    // Process renewal
    $('#formRenovarPrestamo').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../controllers/renovarPrestamo.php',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    icon: response.exito ? 'success' : 'error',
                    title: response.exito ? '¡prestamo renovado!' : 'error',
                    text: response.mensaje,
                    timer: response.exito ? 2000 : null,
                    showConfirmButton: !response.exito
                }).then(() => {
                    if (response.exito) {
                        $('#modalRenovarPrestamo').modal('hide');
                        location.reload();
                    }
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error del sistema',
                    text: 'no se pudo procesar la renovacion'
                });
            }
        });
    });

    // View details
    $(document).on('click', '.btnVerDetalle, .btnVerDetalleCliente', function() {
        const prestamoId = $(this).data('id');
        
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        $('#contenidoDetallePrestamo').html('');
        
        setTimeout(() => {
            $('#contenidoDetallePrestamo').html(`
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `);
            
            $('#modalDetallePrestamo').modal('show');
            
            setTimeout(() => {
                $('#contenidoDetallePrestamo').html(`
                    <div class="alert alert-info">
                        <h6>detalles del prestamo #${prestamoId}</h6>
                        <p>esta funcionalidad se puede expandir para mostrar mas detalles del prestamo.</p>
                    </div>
                `);
            }, 1000);
        }, 500);
    });

    // Modal cleanup
    $('.modal').on('hidden.bs.modal', function() {
        const form = $(this).find('form')[0];
        if (form) form.reset();
        $(this).find('.modal-body').find('.spinner-border').parent().remove();
        
        setTimeout(() => {
            if ($('.modal.show').length === 0) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
            }
        }, 150);
    });
    
    $('.modal').on('show.bs.modal', function() {
        $('.modal-backdrop').not('.show').remove();
    }).on('shown.bs.modal', function() {
        if ($('.modal-backdrop').length > 1) {
            $('.modal-backdrop').not(':last').remove();
        }
    });

    // Global modal cleanup
    window.limpiarModalesBloqueados = function() {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('overflow', '').css('padding-right', '');
    };
    
    $(document).keyup(function(e) {
        if (e.keyCode === 27) window.limpiarModalesBloqueados();
    });
});

// Helper functions
function cargarPrestamosVencidos() {
    $.get('../controllers/listarPrestamos.php?tipo=vencidos', function(response) {
        if (response.exito) {
            response.datos.length === 0 
                ? Swal.fire({icon: 'info', title: 'sin prestamos vencidos', text: 'no hay prestamos vencidos en este momento'})
                : mostrarTablaVencidos(response.datos);
        }
    }, 'json');
}

function mostrarEstadisticas() {
    $.get('../controllers/listarPrestamos.php?tipo=estadisticas', function(response) {
        if (response.exito) mostrarModalEstadisticas(response.datos);
    }, 'json');
}

function mostrarTablaVencidos(prestamos) {
    const rows = prestamos.map(p => `
        <tr>
            <td>${p.usuario_nombre}</td>
            <td>${p.libro_titulo}</td>
            <td><span class="badge bg-danger">${p.dias_vencido} dias</span></td>
            <td><small>${p.usuario_email}<br>${p.usuario_telefono}</small></td>
        </tr>
    `).join('');
    
    const modal = `
        <div class="modal fade" id="modalVencidos" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="zmdi zmdi-alert-triangle text-danger"></i> 
                            prestamos vencidos (${prestamos.length})
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr><th>usuario</th><th>libro</th><th>dias vencido</th><th>contacto</th></tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modal);
    $('#modalVencidos').modal('show').on('hidden.bs.modal', function() { $(this).remove(); });
}

function mostrarModalEstadisticas(stats) {
    const librosRows = stats.libros_populares.map(libro => `
        <tr>
            <td>${libro.titulo}</td>
            <td>${libro.autor}</td>
            <td><span class="badge bg-primary">${libro.total_prestamos}</span></td>
        </tr>
    `).join('');
    
    const modal = `
        <div class="modal fade" id="modalEstadisticas" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="zmdi zmdi-chart"></i> estadisticas de prestamos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white text-center">
                                    <div class="card-body">
                                        <h3>${stats.prestamos_activos}</h3>
                                        <p class="mb-0">prestamos activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white text-center">
                                    <div class="card-body">
                                        <h3>${stats.prestamos_vencidos}</h3>
                                        <p class="mb-0">prestamos vencidos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white text-center">
                                    <div class="card-body">
                                        <h3>${stats.prestamos_devueltos}</h3>
                                        <p class="mb-0">Libros Devueltos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white text-center">
                                    <div class="card-body">
                                        <h3>${stats.prestamos_activos + stats.prestamos_devueltos}</h3>
                                        <p class="mb-0">total prestamos</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h6>libros mas prestados</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>libro</th><th>autor</th><th>total prestamos</th></tr>
                                </thead>
                                <tbody>${librosRows}</tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modal);
    $('#modalEstadisticas').modal('show').on('hidden.bs.modal', function() { $(this).remove(); });
}