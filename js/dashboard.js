/**
 * Dashboard JavaScript Functions - Sistema limpio y funcional
 */

$(document).ready(function() {
    console.log('🚀 Dashboard Biblioteca SENAP inicializado');

    // Función global para limpiar modales problemáticos
    window.limpiarModalesBloqueados = function() {
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css({
            'overflow': '',
            'padding-right': ''
        });
        console.log('🧹 Modales limpiados');
    };

    // Manejador global para errores
    window.addEventListener('error', function(e) {
        console.error('❌ Error detectado:', e.error);
    });

    // Escuchar tecla ESC para limpiar modales
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            window.limpiarModalesBloqueados();
        }
    });

    console.log('✅ Sistema de dashboard listo');
});