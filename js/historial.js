$(document).ready(function() {
    cargarHistorial();

    function cargarHistorial() {
        $.ajax({
            url: '/Biblioteca-2025/controllers/obtenerHistorial.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No autorizado',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                const tabla = $('#tablaHistorial').DataTable({
                    destroy: true,
                    data: data,
                    columns: [
                        { data: 'id' },
                        { data: 'usuario' },
                        { data: 'libro' },
                        { data: 'fecha_prestamo' },
                        { data: 'fecha_devolucion' },
                        { data: 'estado' }
                    ],
                    language: {
                        sProcessing: "Procesando...",
                        sLengthMenu: "Mostrar _MENU_ registros",
                        sZeroRecords: "No se encontraron resultados",
                        sEmptyTable: "Ningún dato disponible en esta tabla",
                        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                        sSearch: "Buscar:",
                        sLoadingRecords: "Cargando...",
                        oPaginate: {
                            sFirst: "Primero",
                            sLast: "Ultimo",
                            sNext: "Siguiente",
                            sPrevious: "Anterior"
                        }
                    }
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al cargar el historial',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
});
