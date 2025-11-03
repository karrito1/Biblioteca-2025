$(document).ready(function() {
    $('.btn-reporte').on('click', function() {
        const tipo = $(this).data('tipo');
        const formato = $(this).data('formato');
        const nombreFormato = formato.toUpperCase();
        const nombreTipo = tipo.charAt(0).toUpperCase() + tipo.slice(1);

        Swal.fire({
            title: 'Generando reporte...',
            html: `Se esta generando el reporte de ${nombreTipo} en formato ${nombreFormato}`,
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                window.location.href = `/Biblioteca-2025/controllers/reportes.php?tipo=${tipo}&formato=${formato}`;
                
                setTimeout(() => {
                    Swal.fire({
                        title: 'Exito',
                        text: `Reporte de ${nombreTipo} descargado correctamente`,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                }, 2000);
            }
        });
    });
});
