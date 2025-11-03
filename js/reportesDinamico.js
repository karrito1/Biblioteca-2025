$(document).ready(function() {
    
    $("#btnReportes").on("click", function(e) {
        e.preventDefault();
        cargarReportes();
    });

    function cargarReportes() {
        // ocultar otros contenedores
        $("#tablaUsuariosContainer").hide();
        $("#tablaLibrosContainer").hide();
        $("#tablaLibroscliente").hide();
        $("#tablaReservasCliente").hide();
        $("#tablaPrestamosContainer").hide();
        $("#historialContainer").hide();

        // limpiar contenedor
        $("#reportesContainer").empty();

        // crear interfaz de reportes
        const html = `
            <div class="card p-4 mb-5 shadow">
                <h3 class="mb-4"><i class="zmdi zmdi-trending-up"></i> Generar Reportes</h3>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Usuarios</h5>
                                <p class="card-text">Descargar reporte de usuarios registrados</p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-primary btn-reporte-dinamico" data-tipo="usuarios" data-formato="excel">
                                        <i class="zmdi zmdi-file-excel"></i> Excel
                                    </button>
                                    <button class="btn btn-danger btn-reporte-dinamico" data-tipo="usuarios" data-formato="pdf">
                                        <i class="zmdi zmdi-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Libros</h5>
                                <p class="card-text">Descargar reporte de libros registrados</p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-primary btn-reporte-dinamico" data-tipo="libros" data-formato="excel">
                                        <i class="zmdi zmdi-file-excel"></i> Excel
                                    </button>
                                    <button class="btn btn-danger btn-reporte-dinamico" data-tipo="libros" data-formato="pdf">
                                        <i class="zmdi zmdi-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Prestamos</h5>
                                <p class="card-text">Descargar reporte de prestamos</p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-primary btn-reporte-dinamico" data-tipo="prestamos" data-formato="excel">
                                        <i class="zmdi zmdi-file-excel"></i> Excel
                                    </button>
                                    <button class="btn btn-danger btn-reporte-dinamico" data-tipo="prestamos" data-formato="pdf">
                                        <i class="zmdi zmdi-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $("#reportesContainer").html(html);
        $("#reportesContainer").slideDown(400);

        // agregar event listeners a botones dinamicos
        $(document).off('click', '.btn-reporte-dinamico').on('click', '.btn-reporte-dinamico', function() {
            const tipo = $(this).data('tipo');
            const formato = $(this).data('formato');
            generarReporte(tipo, formato);
        });
    }

    function generarReporte(tipo, formato) {
        const nombreFormato = formato.toUpperCase();
        const nombreTipo = tipo.charAt(0).toUpperCase() + tipo.slice(1);

        Swal.fire({
            title: 'Descargando reporte...',
            html: `Se esta generando el reporte de ${nombreTipo} en formato ${nombreFormato}`,
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                setTimeout(function() {
                    window.location.href = `/Biblioteca-2025/controllers/reportes.php?tipo=${tipo}&formato=${formato}`;
                    
                    setTimeout(function() {
                        Swal.fire({
                            title: 'Exito',
                            text: `Reporte de ${nombreTipo} descargado correctamente`,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }, 1000);
                }, 500);
            }
        });
    }
});
