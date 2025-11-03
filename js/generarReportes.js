$(document).ready(function() {
    
    $('.btn-reporte').on('click', function() {
        const tipo = $(this).data('tipo');
        const formato = $(this).data('formato');
        
        generarReporte(tipo, formato);
    });

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
        let filas = [];

        if (tipo === 'usuarios') {
            titulo = 'Reporte de Usuarios';
            columnas = [
                { header: 'ID', dataKey: 'id' },
                { header: 'Nombre', dataKey: 'nombre' },
                { header: 'Email', dataKey: 'email' },
                { header: 'Telefono', dataKey: 'telefono' },
                { header: 'Estado', dataKey: 'estado' }
            ];
            filas = datos;
        } else if (tipo === 'libros') {
            titulo = 'Reporte de Libros';
            columnas = [
                { header: 'ID', dataKey: 'id' },
                { header: 'Titulo', dataKey: 'titulo' },
                { header: 'Autor', dataKey: 'autor' },
                { header: 'ISBN', dataKey: 'isbn' },
                { header: 'Cantidad', dataKey: 'cantidad' }
            ];
            filas = datos;
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
            filas = datos;
        }

        doc.setFontSize(16);
        doc.text(titulo, 14, 15);

        doc.autoTable({
            columns: columnas,
            body: filas,
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
