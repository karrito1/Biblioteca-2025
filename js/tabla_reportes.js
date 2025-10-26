// tabla_reportes.js - funcionalidad para reportes y estadisticas

/**
 * Inicializa el grafico de prestamos mensuales
 * @param {Array} prestamosData - Datos de prestamos mensuales
 */
function initGraficoPrestamosMensuales(prestamosData) {
    if (!prestamosData || prestamosData.length === 0) {
        console.warn('no hay datos para el grafico de prestamos mensuales');
        return;
    }
    
    const meses = prestamosData.map(item => {
        const fecha = new Date(item.mes + '-01');
        return fecha.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
    });
    const cantidades = prestamosData.map(item => item.total_prestamos);

    // crear grafico
    const ctx = document.getElementById('chartPrestamosMensuales').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'prestamos',
                data: cantidades,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'tendencia de prestamos'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

/**
 * Inicializa el grafico de distribucion de prestamos
 */
function initGraficoPrestamos() {
    const ctx = document.getElementById('chartPrestamos');
    if (!ctx) return;
    
    const datos = window.datosPrestamos || { activos: 0, vencidos: 0, disponibles: 0 };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['prestamos activos', 'prestamos vencidos', 'libros disponibles'],
            datasets: [{
                data: [datos.activos, datos.vencidos, datos.disponibles],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'estado de libros'
                }
            }
        }
    });
}

/**
 * Inicializa el grafico de estado de reservas
 */
function initGraficoReservas() {
    const ctx = document.getElementById('chartReservas');
    if (!ctx) return;
    
    const datos = window.datosReservas || { pendientes: 0, aprobadas: 0, rechazadas: 0 };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['pendientes', 'aprobadas', 'rechazadas'],
            datasets: [{
                data: [datos.pendientes, datos.aprobadas, datos.rechazadas],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'estado de reservas'
                }
            }
        }
    });
}

/**
 * Carga la actividad reciente
 */
function cargarActividadReciente() {
    const container = $('#actividadReciente');
    
    // mostrar indicador de carga
    container.html('<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> cargando actividad...</div>');
    
    // simulacion de datos (implementar segun necesidades)
    setTimeout(() => {
        const actividadHTML = `
            <div class="list-group">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">nuevo prestamo registrado</h6>
                        <small>hace 5 min</small>
                    </div>
                    <p class="mb-1">usuario: juan perez - libro: cien anos de soledad</p>
                </div>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">libro devuelto</h6>
                        <small>hace 15 min</small>
                    </div>
                    <p class="mb-1">usuario: maria garcia - libro: el principito</p>
                </div>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">nueva reserva</h6>
                        <small>hace 30 min</small>
                    </div>
                    <p class="mb-1">usuario: carlos lopez - libro: don quijote</p>
                </div>
            </div>
        `;
        container.html(actividadHTML);
    }, 1000);
}

$(document).ready(function() {
    // configurar fechas por defecto para reportes personalizados
    const hoy = new Date();
    const hace30Dias = new Date();
    hace30Dias.setDate(hoy.getDate() - 30);
    
    $('#fechaInicio').val(hace30Dias.toISOString().split('T')[0]);
    $('#fechaFin').val(hoy.toISOString().split('T')[0]);

    // cargar actividad inicial
    cargarActividadReciente();
    
    // actualizar actividad cada 30 segundos
    setInterval(cargarActividadReciente, 30000);

    // inicializar grafico si hay datos disponibles
    if (typeof window.prestamosData !== 'undefined' && window.prestamosData) {
        initGraficoPrestamosMensuales(window.prestamosData);
    }
    
    // inicializar graficos adicionales
    setTimeout(() => {
        initGraficoPrestamos();
        initGraficoReservas();
    }, 500);

    // manejar formulario de reporte personalizado
    $('#formReportePersonalizado').submit(function(e) {
        e.preventDefault();
        
        const tipo = $('#tipoReporte').val();
        const fechaInicio = $('#fechaInicio').val();
        const fechaFin = $('#fechaFin').val();
        
        if (!tipo) {
            Swal.fire({
                icon: 'warning',
                title: 'tipo requerido',
                text: 'selecciona el tipo de reporte a generar'
            });
            return;
        }
        
        if (!fechaInicio || !fechaFin) {
            Swal.fire({
                icon: 'warning',
                title: 'fechas requeridas',
                text: 'selecciona el rango de fechas para el reporte'
            });
            return;
        }
        
        if (new Date(fechaInicio) > new Date(fechaFin)) {
            Swal.fire({
                icon: 'warning',
                title: 'fechas invalidas',
                text: 'la fecha de inicio debe ser anterior a la fecha de fin'
            });
            return;
        }
        
        generarReportePersonalizado(tipo, fechaInicio, fechaFin);
    });
});

// funcion para generar reportes
function generarReportePDF(tipo) {
    Swal.fire({
        title: 'generando reporte pdf...',
        text: 'por favor espera mientras se genera el documento',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // simular generacion de pdf
    setTimeout(() => {
        const url = `/Biblioteca-2025/controllers/generarReportePDF.php?tipo=${tipo}`;
        window.open(url, '_blank');
        
        Swal.fire({
            icon: 'success',
            title: 'reporte generado',
            text: 'el archivo pdf se ha abierto en una nueva ventana',
            timer: 2000,
            showConfirmButton: false
        });
    }, 2000);
}

function generarReportePersonalizado(tipo, fechaInicio, fechaFin) {
    Swal.fire({
        title: 'generando reporte pdf...',
        text: 'por favor espera mientras se genera el documento personalizado',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const params = new URLSearchParams({
        tipo: tipo,
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin
    });
    
    setTimeout(() => {
        const url = `/Biblioteca-2025/controllers/generarReportePDF.php?${params.toString()}`;
        window.open(url, '_blank');
        
        Swal.fire({
            icon: 'success',
            title: 'reporte personalizado generado',
            html: `
                <p><strong>tipo:</strong> ${tipo}</p>
                <p><strong>periodo:</strong> ${fechaInicio} al ${fechaFin}</p>
                <p><strong>formato:</strong> pdf</p>
            `,
            timer: 3000,
            showConfirmButton: true
        });
    }, 2500);
}

// funciones para estadisticas en tiempo real
function actualizarEstadisticas() {
    $.ajax({
        url: '/Biblioteca-2025/controllers/listarPrestamos.php?tipo=estadisticas',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.exito) {
                const stats = response.datos;
                
                // actualizar tarjetas de estadisticas
                $('.card.bg-success h3').text(stats.prestamos_activos);
                $('.card.bg-danger h3').text(stats.prestamos_vencidos);
                $('.card.bg-secondary h3').text(stats.prestamos_devueltos);
                
                // actualizar graficos si es necesario
                actualizarGraficos(stats);
            }
        },
        error: function() {
            console.log('error al actualizar estadisticas');
        }
    });
}

function actualizarGraficos(stats) {
    // actualizar grafico de prestamos si existe
    if (window.chartPrestamos) {
        // logica para actualizar el grafico existente
        console.log('actualizando graficos con:', stats);
    }
}

// funcion para exportar datos
function exportarEstadisticas() {
    const url = `/Biblioteca-2025/controllers/exportarEstadisticas.php?formato=pdf`;
    window.open(url, '_blank');
    
    Swal.fire({
        icon: 'success',
        title: 'exportando estadisticas',
        text: 'las estadisticas se estan exportando en formato pdf',
        timer: 2000,
        showConfirmButton: false
    });
}

// programar actualizacion automatica de estadisticas cada 5 minutos
setInterval(actualizarEstadisticas, 5 * 60 * 1000);

// funcion para mostrar detalles de libros populares
function mostrarDetallesLibrosPopulares() {
    Swal.fire({
        title: 'libros mas prestados - detalles',
        html: `
            <div class="text-start">
                <p>esta seccion muestra los libros con mayor demanda en la biblioteca.</p>
                <h6>criterios de analisis:</h6>
                <ul>
                    <li>total de prestamos historicos</li>
                    <li>frecuencia de reservas</li>
                    <li>tiempo promedio de prestamo</li>
                    <li>valoraciones de usuarios</li>
                </ul>
                <div class="alert alert-info mt-3">
                    <i class="zmdi zmdi-info"></i>
                    <strong>sugerencia:</strong> considera adquirir mas ejemplares 
                    de los titulos mas populares para reducir tiempos de espera.
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: 'entendido'
    });
}

// funcion para mostrar detalles de usuarios activos
function mostrarDetallesUsuariosActivos() {
    Swal.fire({
        title: 'usuarios mas activos - detalles',
        html: `
            <div class="text-start">
                <p>esta seccion identifica a los usuarios con mayor actividad en la biblioteca.</p>
                <h6>metricas incluidas:</h6>
                <ul>
                    <li>numero total de prestamos</li>
                    <li>frecuencia de visitas</li>
                    <li>puntualidad en devoluciones</li>
                    <li>diversidad de generos leidos</li>
                </ul>
                <div class="alert alert-success mt-3">
                    <i class="zmdi zmdi-star"></i>
                    <strong>idea:</strong> considera implementar un programa de fidelizacion 
                    para estos usuarios frecuentes.
                </div>
            </div>
        `,
        width: 600,
        confirmButtonText: 'cerrar'
    });
}

/**
 * Exporta datos a Excel
 * @param {string} tipo - Tipo de datos a exportar
 */
function exportarExcel(tipo) {
    Swal.fire({
        title: 'exportando a excel...',
        text: 'preparando archivo',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // simular exportacion a excel
    setTimeout(() => {
        try {
            // crear datos de ejemplo para excel
            let datos = [];
            let headers = [];
            
            switch(tipo) {
                case 'prestamos':
                    headers = ['ID', 'Usuario', 'Libro', 'Fecha Prestamo', 'Fecha Devolucion', 'Estado'];
                    datos = [
                        ['1', 'juan perez', 'cien anos de soledad', '2025-10-01', '2025-10-15', 'activo'],
                        ['2', 'maria garcia', 'el principito', '2025-10-02', '2025-10-16', 'devuelto'],
                        ['3', 'carlos lopez', 'don quijote', '2025-10-03', '2025-10-17', 'activo']
                    ];
                    break;
                case 'usuarios':
                    headers = ['ID', 'Nombre', 'Email', 'Telefono', 'Rol'];
                    datos = [
                        ['1', 'juan perez', 'juan@email.com', '123456789', 'cliente'],
                        ['2', 'maria garcia', 'maria@email.com', '987654321', 'cliente'],
                        ['3', 'admin user', 'admin@email.com', '555666777', 'administrador']
                    ];
                    break;
            }
            
            // crear archivo CSV (compatible con Excel)
            let csvContent = '\uFEFF' + headers.join(',') + '\n'; // BOM para UTF-8
            datos.forEach(fila => {
                csvContent += fila.join(',') + '\n';
            });
            
            // descargar archivo
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `reporte_${tipo}_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            Swal.fire({
                icon: 'success',
                title: 'exportacion completada',
                text: `archivo excel de ${tipo} descargado exitosamente`,
                timer: 2000,
                showConfirmButton: false
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: 'no se pudo exportar a excel'
            });
        }
    }, 1500);
}

// exportar funciones para uso global
window.reportes = {
    generarReportePDF,
    generarReportePersonalizado,
    actualizarEstadisticas,
    exportarEstadisticas,
    mostrarDetallesLibrosPopulares,
    mostrarDetallesUsuariosActivos,
    exportarExcel
};