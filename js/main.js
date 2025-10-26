$(document).ready(function(){
    $('.tooltips-general').tooltip('hide');
<<<<<<< HEAD
    
    // Debug: Verify jQuery and document ready
    // test ajax connectivity
    $.get('tabla_usuarios.php')
        .fail(function(xhr) { console.log('tabla_usuarios.php failed:', xhr.status); });
    
    $.get('tabla_inventario.php')
        .fail(function(xhr) { console.log('tabla_inventario.php failed:', xhr.status); });
    
    // Dashboard navigation using original container system
    $(document).on('click', '#btnUsuarios', function() {
        console.log('Usuarios button clicked');
        hideAllContainers();
        loadTablaUsuarios();
    });
    
    $(document).on('click', '#btnLibros', function() {
        hideAllContainers();
        loadTablaLibros();
    });
    
    $(document).on('click', '#btnLibrosMenu', function() {
        hideAllContainers();
        loadTablaLibros();
    });
    
    $(document).on('click', '#btnPrestamos', function() {
        window.navigationHandled = true;
        console.log('Prestamos button clicked');
        hideAllContainers();
        loadTablaPrestamos();
    });
    
    $(document).on('click', '#btnReservas', function() {
        window.navigationHandled = true;
        console.log('Reservas button clicked');
        hideAllContainers();
        loadTablaReservas();
    });
    
    $(document).on('click', '#btnReportes', function() {
        window.navigationHandled = true;
        console.log('Reportes button clicked');
        hideAllContainers();
        loadTablaReportes();
    });
    
    $(document).on('click', '#btnInventario', function() {
        window.navigationHandled = true;
        console.log('Inventario button clicked');
        hideAllContainers();
        loadTablaInventario();
    });
    
    $(document).on('click', '#btnCliente', function() {
        hideAllContainers();
        loadLibrosClientes();
    });
    
    $(document).on('click', '#btnMisReservas', function() {
        hideAllContainers();
        loadReservasClientes();
    });
    
    $(document).on('click', '#btnMisPrestamos', function() {
        hideAllContainers();
        loadMisPrestamos();
    });

    function hideAllContainers() {
        window.navigationHandled = false;
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        // hide all table containers individually for better debugging
        $('#tablaUsuariosContainer').hide();
        $('#tablaLibrosContainer').hide();
        $('#tablaPrestamosContainer').hide();
        $('#tablaReservasContainer').hide();
        $('#tablaReportesContainer').hide();
        $('#tablaInventarioContainer').hide();
        $('#tablaLibroscliente').hide();
        $('#tablaReservasCliente').hide();
        $('#tablaMisPrestamosContainer').hide();
    }

    function loadTablaUsuarios() {
        if ($.fn.DataTable.isDataTable('#tablausuarios')) {
            $('#tablausuarios').DataTable().destroy();
        }
        $('#tablaUsuariosContainer').empty().load('tabla_usuarios.php', function() {
            $('#tablaUsuariosContainer').slideDown(400);
            if ($('#tablausuarios').length) {
                $('#tablausuarios').DataTable({
                    language: { url: '../js/es-ES.json' },
                    destroy: true
                });
            }
        });
    }

    function loadTablaLibros() {
        if ($.fn.DataTable.isDataTable('#tablaLibros')) {
            $('#tablaLibros').DataTable().destroy();
        }
        $('#tablaLibrosContainer').empty().load('tabla_libros.php', function() {
            $('#tablaLibrosContainer').slideDown(400);
        });
    }

    function loadTablaPrestamos() {
        window.navigationHandled = true;
        if ($.fn.DataTable.isDataTable('#tablaprestamos')) {
            $('#tablaprestamos').DataTable().destroy();
        }
        $('#tablaPrestamosContainer').empty().load('tabla_prestamos.php', function(response, status, xhr) {
            if (status === 'error') {
                console.error('Error loading tabla_prestamos.php:', xhr.status, xhr.statusText);
                $('#tablaPrestamosContainer').html('<div class="alert alert-danger">Error al cargar prestamos: ' + xhr.statusText + '</div>');
            } else {
                $('#tablaPrestamosContainer').show().slideDown(400);
            }
        });
    }

    function loadTablaReservas() {
        window.navigationHandled = true;
        if ($.fn.DataTable.isDataTable('#tablareservas')) {
            $('#tablareservas').DataTable().destroy();
        }
        $('#tablaReservasContainer').empty().load('tabla_reservas.php', function(response, status, xhr) {
            if (status === 'error') {
                console.error('Error loading tabla_reservas.php:', xhr.status, xhr.statusText);
                $('#tablaReservasContainer').html('<div class="alert alert-danger">Error al cargar reservas: ' + xhr.statusText + '</div>');
            } else {
                $('#tablaReservasContainer').show().slideDown(400);
            }
        });
    }

    function loadTablaReportes() {
        window.navigationHandled = true;
        if ($.fn.DataTable.isDataTable('#tablaReportes')) {
            $('#tablaReportes').DataTable().destroy();
        }
        $('#tablaReportesContainer').empty().load('tabla_reportes.php', function(response, status, xhr) {
            if (status === 'error') {
                console.error('Error loading tabla_reportes.php:', xhr.status, xhr.statusText);
                $('#tablaReportesContainer').html('<div class="alert alert-danger">Error al cargar reportes: ' + xhr.status + ' - ' + xhr.statusText + '</div>');
            } else {
                $('#tablaReportesContainer').show().slideDown(400);
            }
        });
    }

    function loadTablaInventario() {
        window.navigationHandled = true;
        if ($.fn.DataTable.isDataTable('#tablaInventario')) {
            $('#tablaInventario').DataTable().destroy();
        }
        $('#tablaInventarioContainer').empty().load('tabla_inventario.php', function(response, status, xhr) {
            if (status === 'error') {
                console.error('Error loading tabla_inventario.php:', xhr.status, xhr.statusText);
                $('#tablaInventarioContainer').html('<div class="alert alert-danger">Error al cargar inventario: ' + xhr.statusText + '</div>');
            } else {
                console.log('Tabla inventario loaded successfully');
                $('#tablaInventarioContainer').show().slideDown(400);
            }
        });
    }

    function loadLibrosClientes() {
        $('#tablaLibroscliente').empty().load('librosClientes.php', function() {
            $('#tablaLibroscliente').slideDown(400);
        });
    }

    function loadReservasClientes() {
        $('#tablaReservasCliente').empty().load('reservasClientes.php', function() {
            $('#tablaReservasCliente').slideDown(400);
        });
    }

    function loadMisPrestamos() {
        $('#tablaMisPrestamosContainer').empty().load('prestamosClientes.php', function() {
            $('#tablaMisPrestamosContainer').slideDown(400);
        });
    }

    function cargarContenido(archivo) {
        // Legacy function kept for compatibility
        if (typeof $ !== 'undefined' && $.fn.modal) {
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }
        
        const contenedor = $('.content-page-container');
        if (contenedor.length > 0) {
            $.get(archivo, data => contenedor.html(data))
             .fail(() => contenedor.html('<div class="alert alert-danger">Error al cargar el contenido</div>'));
        }
    }
    
    $('.mobile-menu-button').on('click', function(){
        const mobileMenu = $('.navbar-lateral');
        mobileMenu.css('display') === 'none' ? mobileMenu.fadeIn(300) : mobileMenu.fadeOut(300);
    });
    
    $('.desktop-menu-button').on('click', function(e){
        e.preventDefault();
        const NavLateral = $('.navbar-lateral');
        const ContentPage = $('.content-page-container');
        
        if(NavLateral.hasClass('desktopMenu')){
            NavLateral.removeClass('desktopMenu');
            ContentPage.removeClass('desktopMenu');
        } else {
=======
    $('.mobile-menu-button').on('click', function(){
        var mobileMenu=$('.navbar-lateral');	
        if(mobileMenu.css('display')=='none'){
            mobileMenu.fadeIn(300);
        }else{
            mobileMenu.fadeOut(300);
        }
    });
    $('.desktop-menu-button').on('click', function(e){
        e.preventDefault();
        var NavLateral=$('.navbar-lateral'); 
        var ContentPage=$('.content-page-container');   
        if(NavLateral.hasClass('desktopMenu')){
            NavLateral.removeClass('desktopMenu');
            ContentPage.removeClass('desktopMenu');
        }else{
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
            NavLateral.addClass('desktopMenu');
            ContentPage.addClass('desktopMenu');
        }
    });
    $('.dropdown-menu-button').on('click', function(e){
        e.preventDefault();
        var icon=$(this).children('.icon-sub-menu');
        if(icon.hasClass('zmdi-chevron-down')){
            icon.removeClass('zmdi-chevron-down').addClass('zmdi-chevron-up');
            $(this).addClass('dropdown-menu-button-active');
        }else{
            icon.removeClass('zmdi-chevron-up').addClass('zmdi-chevron-down');
            $(this).removeClass('dropdown-menu-button-active');
        }
        
        var dropMenu=$(this).next('ul');
        dropMenu.slideToggle('slow');
    });
    $('.exit-system-button').on('click', function(e){
        e.preventDefault();
        var LinkExitSystem=$(this).attr("data-href");
        swal({
<<<<<<< HEAD
            title: "estas seguro?",
            text: "quieres salir del sistema y cerrar la sesion actual",
=======
            title: "¿Estás seguro?",
            text: "Quieres salir del sistema y cerrar la sesión actual",
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            confirmButtonText: "Si, salir",
            cancelButtonText: "No, cancelar",
            animation: "slide-from-top",
            closeOnConfirm: false 
        },function(){
            window.location=LinkExitSystem; 
        });  
    });
    $('.search-book-button').click(function(e){
        e.preventDefault();
        var LinkSearchBook=$(this).attr("data-href");
        swal({
<<<<<<< HEAD
           title: "que libro estas buscando?",
=======
           title: "¿Qué libro estás buscando?",
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
           text: "Por favor escribe el nombre del libro",
           type: "input",   
           showCancelButton: true,
           closeOnConfirm: false,
           animation: "slide-from-top",
           cancelButtonText: "Cancelar",
           confirmButtonText: "Buscar",
           confirmButtonColor: "#3598D9",
<<<<<<< HEAD
           inputPlaceholder: "escribe aqui el nombre de libro" }, 
=======
           inputPlaceholder: "Escribe aquí el nombre de libro" }, 
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
      function(inputValue){
           if (inputValue === false) return false;  

           if (inputValue === "") {
               swal.showInputError("Debes escribir el nombre del libro");     
               return false;   
           } 
            window.location=LinkSearchBook+"?bookName="+inputValue;
       });
    });
    $('.btn-help').on('click', function(){
        $('#ModalHelp').modal({
            show: true,
            backdrop: "static"
        });
    });
<<<<<<< HEAD
    

});

=======
});
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
(function($){
    $(window).load(function(){
        $(".nav-lateral-scroll").mCustomScrollbar({
            theme:"light-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons:{ enable: true }
        });
        $(".custom-scroll-containers").mCustomScrollbar({
            theme:"dark-thin",
            scrollbarPosition: "inside",
            autoHideScrollbar: true,
            scrollButtons:{ enable: true }
        });
    });
<<<<<<< HEAD
})(jQuery);

// funcion global para limpiar modales bloqueados
function limpiarModalesBloqueados() {
    $('.modal').modal('hide');
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    $('body').css('padding-right', '');
    
    // mostrar confirmacion
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'limpieza completada',
            text: 'los modales han sido limpiados correctamente',
            timer: 1500,
            showConfirmButton: false
        });
    }
}
=======
})(jQuery);
>>>>>>> e1a7a7d4b0d8393da743ce7a776d9116b6e3a264
