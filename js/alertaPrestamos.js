// alertaPrestamos.js - funciones simples para prestamos

$(document).ready(function() {
    // manejar click en boton de prestamos
    $('#btnPrestamos').click(function() {
        cargarTablaPrestamos();
    });
});

// cargar tabla de prestamos
function cargarTablaPrestamos() {
    $('#tablaUsuariosContainer, #tablaLibrosContainer, #tablaLibroscliente, #tablaReservasCliente, #tablaMisPrestamosContainer').hide();
    $('#tablaPrestamosContainer').show();
    
    $.ajax({
        url: '../controllers/agregarPrestamos.php',
        type: 'GET',
        success: function(response) {
            $('#tablaPrestamosContainer').html(response);
        },
        error: function() {
            Swal.fire('error', 'no se pudo cargar la tabla de prestamos', 'error');
        }
    });
}