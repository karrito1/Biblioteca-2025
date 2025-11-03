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
            title: 'Generando reporte...',
            html: `Se esta generando el reporte de ${nombreTipo} en formato ${nombreFormato}`,
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                
                $.ajax({
                    url: `/Biblioteca-2025/controllers/reportes.php?tipo=${tipo}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(datos) {
                        if (formato === 'pdf') {
                            generarPDF(datos, tipo);
                        } else if (formato === 'excel') {
                            generarExcel(datos, tipo);
                        }
                        
                        Swal.fire({
                            title: 'Exito',
                            text: `Reporte de ${nombreTipo} descargado correctamente`,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al generar el reporte',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }
        });
    }

    function generarPDF(datos, tipo) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        let titulo = '';
        let columnas = [];

        if (tipo === 'usuarios') {
            titulo = 'Reporte de Usuarios';
            columnas = [
                { header: 'ID', dataKey: 'id' },
                { header: 'Nombre', dataKey: 'nombre' },
                { header: 'Email', dataKey: 'email' },
                { header: 'Telefono', dataKey: 'telefono' },
                { header: 'Estado', dataKey: 'estado' }
            ];
        } else if (tipo === 'libros') {
            titulo = 'Reporte de Libros';
            columnas = [
                { header: 'ID', dataKey: 'id' },
                { header: 'Titulo', dataKey: 'titulo' },
                { header: 'Autor', dataKey: 'autor' },
                { header: 'ISBN', dataKey: 'isbn' },
                { header: 'Cantidad', dataKey: 'cantidad' }
            ];
        } else if (tipo === 'prestamos') {
            titulo = 'Reporte de Prestamos';
            columnas = [
                { header: 'ID', dataKey: 'id' },
                { header: 'Usuario', dataKey: 'usuario' },
                { header: 'Libro', dataKey: 'libro' },
                { header: 'Fecha Prestamo', dataKey: 'fecha_prestamo' },
                { header: 'Fecha Devolucion', dataKey: 'fecha_devolucion' },
                { header: 'Estado', dataKey: 'estado' }
            ];
        }

        doc.setFontSize(16);
        doc.text(titulo, 14, 15);

        doc.autoTable({
            columns: columnas,
            body: datos,
            startY: 25,
            theme: 'grid',
            headStyles: {
                fillColor: [41, 128, 185],
                textColor: [255, 255, 255],
                fontStyle: 'bold'
            },
            alternateRowStyles: {
                fillColor: [240, 240, 240]
            }
        });

        doc.save(`reporte_${tipo}.pdf`);
    }

    function generarExcel(datos, tipo) {
        let titulo = '';
        let columnas = [];

        if (tipo === 'usuarios') {
            titulo = 'Reporte de Usuarios';
            columnas = ['id', 'nombre', 'email', 'telefono', 'estado'];
        } else if (tipo === 'libros') {
            titulo = 'Reporte de Libros';
            columnas = ['id', 'titulo', 'autor', 'isbn', 'cantidad'];
        } else if (tipo === 'prestamos') {
            titulo = 'Reporte de Prestamos';
            columnas = ['id', 'usuario', 'libro', 'fecha_prestamo', 'fecha_devolucion', 'estado'];
        }

        const worksheet = XLSX.utils.json_to_sheet(datos, { header: columnas });
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, titulo);

        XLSX.writeFile(workbook, `reporte_${tipo}.xlsx`);
    }
});
