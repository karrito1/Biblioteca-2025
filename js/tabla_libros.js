// tabla_libros.js - Versión simplificada
$(document).ready(function () {
  // Función para verificar si DataTables está disponible
  function verificarDataTables() {
    if (typeof $.fn.DataTable === 'undefined') {
      console.error('DataTables no está cargado');
      return false;
    }
    return true;
  }

  // Función principal para cargar la tabla
  function cargarTablaLibros() {
    console.log('Iniciando carga de tabla de libros');
    $("#tablaUsuariosContainer").hide();
    $("#tablaLibrosContainer").empty();
    console.log('Contenedor limpiado');

    // Verificar dependencias
    if (!verificarDataTables()) {
      $("#tablaLibrosContainer").html(
        '<div class="alert alert-danger">Error: DataTables no está disponible</div>'
      );
      return;
    }

    // Mostrar loader
    $("#tablaLibrosContainer").html(
      '<div class="text-center"><i class="zmdi zmdi-spinner zmdi-hc-spin"></i> Cargando...</div>'
    );

    // Cargar datos
    $.get("../views/tabla_libros.php")
      .done(function(html) {
        $("#tablaLibrosContainer").html(html);
        
        try {
          // Destruir tabla existente si existe
          var tabla = $("#tablalibros");
          if ($.fn.DataTable.isDataTable(tabla)) {
            tabla.DataTable().destroy();
          }

          // Inicializar nueva tabla con configuración básica
          tabla.DataTable({
            responsive: true,
            language: {
              processing: "Procesando...",
              search: "Buscar:",
              lengthMenu: "Mostrar _MENU_ registros",
              info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
              infoFiltered: "(filtrado de un total de _MAX_ registros)",
              infoPostFix: "",
              loadingRecords: "Cargando...",
              zeroRecords: "No se encontraron resultados",
              emptyTable: "Ningún dato disponible en esta tabla",
              paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
              }
            },
            pageLength: 10,
            order: [[0, 'asc']],
            dom: 'Bfrtip'
          });

          // Mostrar con animación
          $("#tablaLibrosContainer").hide().slideDown(400);
        } catch (error) {
          console.error("Error DataTable:", error);
          $("#tablaLibrosContainer").html(
            '<div class="alert alert-danger">Error al inicializar la tabla</div>'
          );
        }
      })
      .fail(function(jqXHR, textStatus, error) {
        console.error("Error AJAX:", textStatus, error);
        $("#tablaLibrosContainer").html(
          '<div class="alert alert-danger">Error al cargar los datos</div>'
        );
      });
  }

  // Event listeners
  $("#btnLibros, #btnLibrosMenu").on("click", function(e) {
    e.preventDefault();
    cargarTablaLibros();
  });
});