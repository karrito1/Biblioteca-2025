// tabla_reservas.js - Gestión de reservas simplificada

$(document).ready(function() {
    console.log('Inicializando tabla de reservas...');
    
    // Inicializar DataTable básico
    if ($('#tablaReservas').length) {
        try {
            $('#tablaReservas').DataTable({
                language: { url: "/Biblioteca-2025/js/es-ES.json" },
                order: [[0, "desc"]],
                pageLength: 10,
                responsive: true
            });
            console.log('✅ Tabla de reservas inicializada');
        } catch (error) {
            console.error('❌ Error al inicializar tabla de reservas:', error);
        }
    }

    // Filtro por estado
    $('#filtroEstadoReserva').on('change', function() {
        const estado = $(this).val();
        
        if (estado === '') {
            $('#tablaReservas tbody tr').show();
        } else {
            $('#tablaReservas tbody tr').hide();
            $('#tablaReservas tbody tr').each(function() {
                const badgeText = $(this).find('.badge').text().toLowerCase().trim();
                if (badgeText === estado.toLowerCase()) {
                    $(this).show();
                }
            });
        }
    });

    // Búsqueda básica
    $('#buscarReserva').on('keyup', function() {
        const valor = $(this).val().toLowerCase();
        $('#tablaReservas tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(valor) > -1);
        });
    });

    console.log('📋 Sistema de reservas en modo simplificado');
});