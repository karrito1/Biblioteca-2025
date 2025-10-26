// prestamosclientes.js - funcionalidad especifica para clientes

$(document).ready(function() {
    // funciones especificas para clientes en la gestion de prestamos
    
    // solicitar renovacion de prestamo
    $(document).on('click', '.btnSolicitarRenovacion', function() {
        const prestamoId = $(this).data('id');
        const fechaActual = $(this).data('fecha');
        const libroTitulo = $(this).data('libro');
        
        $('#prestamo_id_cliente').val(prestamoId);
        $('#libroRenovacion').text(libroTitulo);
        $('#fechaActualDevolucion').text(new Date(fechaActual).toLocaleDateString('es-ES'));
        
        // establecer fecha minima (dia siguiente a la fecha actual de devolucion)
        const fecha = new Date(fechaActual);
        fecha.setDate(fecha.getDate() + 1);
        $('#nueva_fecha_devolucion_cliente').attr('min', fecha.toISOString().split('T')[0]);
        
        // establecer fecha por defecto (15 dias mas)
        fecha.setDate(fecha.getDate() + 15);
        $('#nueva_fecha_devolucion_cliente').val(fecha.toISOString().split('T')[0]);
        
        $('#modalSolicitarRenovacion').modal('show');
    });

    // procesar solicitud de renovacion
    $('#formSolicitarRenovacion').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        Swal.fire({
            title: 'solicitar renovacion?',
            text: 'se enviara la solicitud de renovacion para aprobacion',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#d33',
            confirmButtonText: 'si, solicitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/Biblioteca-2025/controllers/renovarPrestamo.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.exito) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Solicitud enviada!',
                                html: `
                                    <p>${response.mensaje}</p>
                                    <div class="alert alert-info mt-3">
                                        <i class="zmdi zmdi-info"></i>
                                        <strong>Nota:</strong> Tu solicitud ha sido procesada. 
                                        Puedes continuar usando el libro hasta la nueva fecha establecida.
                                    </div>
                                `,
                                timer: 3000,
                                showConfirmButton: true
                            }).then(() => {
                                $('#modalSolicitarRenovacion').modal('hide');
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo renovar',
                                html: `
                                    <p>${response.mensaje}</p>
                                    <div class="alert alert-warning mt-3">
                                        <i class="zmdi zmdi-alert-triangle"></i>
                                        <strong>Sugerencia:</strong> Contacta con la biblioteca 
                                        para solicitar la renovacion manualmente.
                                    </div>
                                `
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error del sistema',
                            text: 'no se pudo procesar la solicitud. intenta mas tarde.'
                        });
                    }
                });
            }
        });
    });

    // recordatorio de devolucion
    $(document).on('click', '.btnRecordarDevolucion', function() {
        const libroTitulo = $(this).data('libro');
        const fechaDevolucion = $(this).data('fecha');
        const fecha = new Date(fechaDevolucion);
        const hoy = new Date();
        const diasRestantes = Math.ceil((fecha - hoy) / (1000 * 60 * 60 * 24));
        
        let icono = 'info';
        let mensaje = '';
        let colorAlerta = 'alert-info';
        
        if (diasRestantes < 0) {
            icono = 'error';
            mensaje = `tu prestamo de "${libroTitulo}" esta vencido desde hace ${Math.abs(diasRestantes)} dias.`;
            colorAlerta = 'alert-danger';
        } else if (diasRestantes === 0) {
            icono = 'warning';
            mensaje = `tu prestamo de "${libroTitulo}" vence hoy.`;
            colorAlerta = 'alert-warning';
        } else if (diasRestantes <= 3) {
            icono = 'warning';
            mensaje = `tu prestamo de "${libroTitulo}" vence en ${diasRestantes} dias.`;
            colorAlerta = 'alert-warning';
        } else {
            mensaje = `tu prestamo de "${libroTitulo}" vence en ${diasRestantes} dias.`;
        }
        
        $('#contenidoRecordatorio').html(`
            <div class="${colorAlerta}">
                <h6><i class="zmdi zmdi-calendar"></i> ${libroTitulo}</h6>
                <p>${mensaje}</p>
                <p><strong>fecha de devolucion:</strong> ${fecha.toLocaleDateString('es-ES')}</p>
            </div>
            
            <div class="mt-3">
                <h6>Opciones disponibles:</h6>
                <ul class="list-group">
                    ${diasRestantes > 0 ? `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>solicitar renovacion</span>
                            <button class="btn btn-sm btn-warning" onclick="solicitarRenovacionDirecta()">
                                <i class="zmdi zmdi-refresh"></i> Renovar
                            </button>
                        </li>
                    ` : ''}
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>informacion de contacto de la biblioteca</span>
                        <button class="btn btn-sm btn-info" onclick="mostrarContactoBiblioteca()">
                            <i class="zmdi zmdi-phone"></i> Ver contacto
                        </button>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>ubicacion de la biblioteca</span>
                        <button class="btn btn-sm btn-secondary" onclick="mostrarUbicacionBiblioteca()">
                            <i class="zmdi zmdi-pin"></i> ver ubicacion
                        </button>
                    </li>
                </ul>
            </div>
        `);
        
        $('#modalRecordatorio').modal('show');
    });

    // busqueda de libros para clientes
    window.buscarLibros = function() {
        // redireccionar a la seccion de busqueda de libros
        $('#btnCliente').click();
    };

    // actualizacion automatica de estadisticas cada minuto
    setInterval(function() {
        actualizarEstadisticasCliente();
    }, 60000);
});

// Funciones auxiliares para clientes

function solicitarRenovacionDirecta() {
    $('#modalRecordatorio').modal('hide');
    // buscar el boton de renovacion en la fila correspondiente y hacer clic
    setTimeout(() => {
        $('.btnSolicitarRenovacion').first().click();
    }, 300);
}

function mostrarContactoBiblioteca() {
    Swal.fire({
        icon: 'info',
        title: 'Contacto Biblioteca SENAP',
        html: `
            <div class="text-start">
                <p><i class="zmdi zmdi-phone"></i> <strong>telefono:</strong> (601) 123-4567</p>
                <p><i class="zmdi zmdi-email"></i> <strong>Email:</strong> biblioteca@senap.edu.co</p>
                <p><i class="zmdi zmdi-time"></i> <strong>horario de atencion:</strong></p>
                <ul class="list-unstyled ms-3">
                    <li>Lunes a Viernes: 7:00 AM - 6:00 PM</li>
                    <li>sabados: 8:00 am - 2:00 pm</li>
                    <li>Domingos: Cerrado</li>
                </ul>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

function mostrarUbicacionBiblioteca() {
    Swal.fire({
        icon: 'info',
        title: 'ubicacion biblioteca senap',
        html: `
            <div class="text-start">
                <p><i class="zmdi zmdi-pin"></i> <strong>direccion:</strong> calle 123 #45-67, bogota d.c.</p>
                <p><i class="zmdi zmdi-bus"></i> <strong>transporte publico:</strong></p>
                <ul class="list-unstyled ms-3">
                    <li>• estacion transmilenio mas cercana: portal norte</li>
                    <li>• Rutas de bus: 123, 456, 789</li>
                </ul>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm" onclick="abrirMapa()">
                        <i class="zmdi zmdi-globe"></i> Ver en Google Maps
                    </button>
                </div>
            </div>
        `,
        confirmButtonText: 'Cerrar'
    });
}

function abrirMapa() {
    // Coordenadas de ejemplo - reemplazar con las reales
    const lat = 4.7110;
    const lng = -74.0721;
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
}

function programarRecordatorio() {
    if ('Notification' in window) {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                Swal.fire({
                    icon: 'success',
                    title: 'Recordatorio programado',
                    text: 'recibiras una notificacion cuando se acerque la fecha de devolucion',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // programar notificacion (simplificado - en produccion usar service workers)
                setTimeout(() => {
                    new Notification('Biblioteca SENAP', {
                        body: 'Recuerda devolver tu libro prestado',
                        icon: '/Biblioteca-2025/assets/icons/book.ico'
                    });
                }, 5000); // 5 segundos para demo
                
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Recordatorio manual',
                    text: 'asegurate de revisar regularmente tus prestamos para no perder las fechas de devolucion'
                });
            }
        });
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Recordatorio manual',
            text: 'tu navegador no admite notificaciones. revisa regularmente tus prestamos.'
        });
    }
    
    $('#modalRecordatorio').modal('hide');
}

function actualizarEstadisticasCliente() {
    // actualizar contadores en las tarjetas sin recargar toda la pagina
    const filas = $('#tablaMisPrestamos tbody tr');
    
    let activos = 0, devueltos = 0, vencidos = 0;
    
    filas.each(function() {
        const estado = $(this).data('estado') || $(this).find('.badge').text().toLowerCase().trim();
        
        if (estado === 'activo') {
            activos++;
        } else if (estado === 'devuelto') {
            devueltos++;
        } else if (estado === 'vencido' || $(this).hasClass('table-danger')) {
            vencidos++;
        }
    });
    
    // Actualizar las tarjetas si existen
    $('.card.bg-primary h4').text(activos);
    $('.card.bg-success h4').text(devueltos);
    $('.card.bg-danger h4').text(vencidos);
    $('.card.bg-info h4').text(filas.length);
}