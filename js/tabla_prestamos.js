// tabla_prestamos.js - Gestión de préstamos simplificada

$(document).ready(function() {
    console.log('Inicializando tabla de préstamos...');
    
    // Configuración básica para DataTable
    const tableConfig = {
        language: { url: "../js/es-ES.json" },
        pageLength: 10,
        responsive: true,
        order: [[0, "desc"]]
    };
    
    // Inicializar tabla de préstamos si existe
    if ($('#tablaPrestamos').length) {
        try {
            $('#tablaPrestamos').DataTable(tableConfig);
            console.log('✅ Tabla de préstamos inicializada');
        } catch (error) {
            console.error('❌ Error al inicializar tabla:', error);
        }
    }
    
    // Inicializar tabla de mis préstamos si existe
    if ($('#tablaMisPrestamos').length) {
        try {
            $('#tablaMisPrestamos').DataTable({
                ...tableConfig,
                order: [[3, "desc"]]
            });
            console.log('✅ Tabla de mis préstamos inicializada');
        } catch (error) {
            console.error('❌ Error al inicializar tabla de mis préstamos:', error);
        }
    }

    // Filtros básicos
    $('#filtroEstado, #filtroEstadoCliente').on('change', function() {
        const estado = $(this).val();
        const tabla = $(this).attr('id') === 'filtroEstado' ? '#tablaPrestamos' : '#tablaMisPrestamos';
        
        if (estado === '') {
            $(tabla + ' tbody tr').show();
        } else {
            $(tabla + ' tbody tr').hide();
            $(tabla + ' tbody tr').each(function() {
                const badgeText = $(this).find('.badge').text().toLowerCase().trim();
                if (badgeText.includes(estado.toLowerCase()) || $(this).hasClass('table-' + estado)) {
                    $(this).show();
                }
            });
        }
    });

    // Búsqueda básica
    $('#buscarPrestamo, #buscarPrestamoCliente').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        const tabla = $(this).attr('id') === 'buscarPrestamo' ? '#tablaPrestamos' : '#tablaMisPrestamos';
        
        $(tabla + ' tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    console.log('🔧 Sistema de préstamos en modo simplificado');
});