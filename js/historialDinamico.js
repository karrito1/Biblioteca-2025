$(document).ready(function() {
    
    $("#btnHistorial").on("click", function(e) {
        e.preventDefault();
        cargarHistorial();
    });

    function cargarHistorial() {
        // ocultar otros contenedores
        $("#tablaUsuariosContainer").hide();
        $("#tablaLibrosContainer").hide();
        $("#tablaLibroscliente").hide();
        $("#tablaReservasCliente").hide();
        $("#tablaPrestamosContainer").hide();
        $("#reportesContainer").hide();

        // limpiar contenedor
        if ($.fn.DataTable.isDataTable("#tablaHistorialDinamico")) {
            $("#tablaHistorialDinamico").DataTable().destroy();
        }
        $("#historialContainer").empty();

        // crear html base
        const html = `
            <div class="card p-4 mb-5 shadow">
                <h3 class="mb-4"><i class="zmdi zmdi-calendar"></i> Historial de Prestamos</h3>
                <div class="table-responsive">
                    <table id="tablaHistorialDinamico" class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Libro</th>
                                <th>Fecha Prestamo</th>
                                <th>Fecha Devolucion</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        `;

        $("#historialContainer").html(html);
        $("#historialContainer").slideDown(400);

        // cargar datos
        $.ajax({
            url: '/Biblioteca-2025/controllers/obtenerHistorial.php',
            method: 'GET',
            dataType: 'json',
            success: function(datos) {
                if (datos.error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No autorizado',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                // inicializar datatable
                const tabla = $('#tablaHistorialDinamico').DataTable({
                    data: datos,
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
                        sEmptyTable: "Ningun dato disponible en esta tabla",
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
