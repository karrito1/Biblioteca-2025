<?php
/**
 * Dashboard Functions
 * Funciones reutilizables para el dashboard del sistema bibliotecario
 */

/**
 * Obtiene las estadisticas del dashboard
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @return array - Array con las estadisticas
 */
function obtenerEstadisticasDashboard($baseDatos) {
    $queries = [
        'totalUsuarios' => "SELECT count(*) as count FROM usuarios",
        'totalLibros' => "SELECT count(*) as count FROM libros", 
        'totalReservas' => "SELECT count(*) as count FROM reservas WHERE estado='Pendiente'",
        'totalPrestamos' => "SELECT count(*) as count FROM prestamos WHERE estado='activo'"
    ];

    $stats = [];
    foreach($queries as $key => $query) {
        $result = $baseDatos->efectuarConsulta($query);
        $stats[$key] = mysqli_fetch_assoc($result)['count'];
    }
    
    return $stats;
}

/**
 * Valida la sesion del usuario
 * @return bool - true si la sesion es valida
 */
function validarSesion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../index.php?error=1");
        exit();
        return false;
    }
    return true;
}

/**
 * Obtiene la informacion del usuario de la sesion
 * @return array - Array con nombre y rol del usuario
 */
function obtenerInfoUsuario() {
    return [
        'nombre' => $_SESSION['nombre'] ?? 'Usuario',
        'rol' => $_SESSION['roles'] ?? 'CLIENTE'
    ];
}

/**
 * Genera el menu lateral segun el rol del usuario
 * @param string $rol - Rol del usuario
 * @return string - HTML del menu
 */
function generarMenuLateral($rol) {
    $menu = '<ul class="list-unstyled">';
    $menu .= '<li><a href="/Biblioteca-2025/views/dashboard.php"><i class="zmdi zmdi-home"></i>&nbsp;&nbsp; Inicio</a></li>';
    
    if ($rol === 'ADMINISTRADOR') {
        $menu .= '<li><a href="#" id="btnUsuarios"><i class="zmdi zmdi-accounts"></i>&nbsp;&nbsp; Registro de usuarios</a></li>';
        $menu .= '<li><a href="#" id="btnLibrosMenu"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Registro de libros</a></li>';
        $menu .= '<li><a href="#" id="btnPrestamos"><i class="zmdi zmdi-calendar-check"></i>&nbsp;&nbsp; gestion de prestamos</a></li>';
        $menu .= '<li><a href="#" id="btnInventario"><i class="zmdi zmdi-book"></i>&nbsp;&nbsp; Inventario</a></li>';
    } elseif ($rol === 'CLIENTE') {
        $menu .= '<li><a href="#" id="btnCliente"><i class="zmdi zmdi-search"></i>&nbsp;&nbsp; Buscar libros</a></li>';
        $menu .= '<li><a href="#" id="btnMisReservas"><i class="zmdi zmdi-timer"></i>&nbsp;&nbsp; Mis reservas</a></li>';
        $menu .= '<li><a href="#" id="btnMisPrestamos"><i class="zmdi zmdi-calendar-check"></i>&nbsp;&nbsp; mis prestamos</a></li>';
    }
    
    $menu .= '<li><a href="#" data-bs-toggle="modal" data-bs-target="#modalCerrarSesion">';
    $menu .= '<i class="zmdi zmdi-power"></i>&nbsp;&nbsp; cerrar sesion</a></li>';
    $menu .= '</ul>';
    
    return $menu;
}

/**
 * Genera las tarjetas del dashboard segun el rol
 * @param string $rol - Rol del usuario
 * @param array $stats - Estadisticas para mostrar
 * @return string - HTML de las tarjetas
 */
function generarTarjetasDashboard($rol, $stats) {
    $tarjetas = '';
    
    if ($rol === 'ADMINISTRADOR') {
        $tarjetas .= '<article class="tile" id="btnUsuarios">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Usuarios registrados</div>';
        $tarjetas .= '<div class="tile-num full-reset">' . $stats['totalUsuarios'] . '</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnLibros" style="cursor:pointer;">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-book"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Libros registrados</div>';
        $tarjetas .= '<div class="tile-num full-reset">' . $stats['totalLibros'] . '</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnReservas" style="cursor:pointer;">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-calendar"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Reservas pendientes</div>';
        $tarjetas .= '<div class="tile-num full-reset">' . $stats['totalReservas'] . '</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnPrestamos" style="cursor:pointer;">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-calendar-check"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Prestamos Activos</div>';
        $tarjetas .= '<div class="tile-num full-reset">' . $stats['totalPrestamos'] . '</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnReportes" style="cursor:pointer;">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-trending-up"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Reportes</div>';
        $tarjetas .= '<div class="tile-num full-reset">&nbsp;</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnInventario" style="cursor:pointer;">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-archive"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Inventario</div>';
        $tarjetas .= '<div class="tile-num full-reset">' . $stats['totalLibros'] . '</div>';
        $tarjetas .= '</article>';
        
    } elseif ($rol === 'CLIENTE') {
        $tarjetas .= '<article class="tile" id="btnCliente">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-search"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Libros</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnMisReservas">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-timer"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">Mis reservas</div>';
        $tarjetas .= '</article>';
        
        $tarjetas .= '<article class="tile" id="btnMisPrestamos">';
        $tarjetas .= '<div class="tile-icon full-reset"><i class="zmdi zmdi-calendar-check"></i></div>';
        $tarjetas .= '<div class="tile-name all-tittles">mis prestamos</div>';
        $tarjetas .= '</article>';
    }
    
    return $tarjetas;
}
?>