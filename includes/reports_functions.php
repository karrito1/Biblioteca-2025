<?php
/**
 * Reports Functions
 * Funciones reutilizables para el sistema de reportes y estadisticas
 */

/**
 * Obtiene todas las estadisticas generales del sistema
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @return array - Array con todas las estadisticas
 */
function obtenerEstadisticasGenerales($baseDatos) {
    $estadisticas = [];
    
    // Total de usuarios
    $query = "SELECT COUNT(*) as total FROM usuarios";
    $result = $baseDatos->efectuarConsulta($query);
    $estadisticas['total_usuarios'] = mysqli_fetch_assoc($result)['total'];

    // Total de libros
    $query = "SELECT COUNT(*) as total FROM libros";
    $result = $baseDatos->efectuarConsulta($query);
    $estadisticas['total_libros'] = mysqli_fetch_assoc($result)['total'];

    // Prestamos activos
    $query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
    $result = $baseDatos->efectuarConsulta($query);
    $estadisticas['prestamos_activos'] = mysqli_fetch_assoc($result)['total'];

    // Prestamos vencidos
    $query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo' AND fecha_devolucion < CURDATE()";
    $result = $baseDatos->efectuarConsulta($query);
    $estadisticas['prestamos_vencidos'] = mysqli_fetch_assoc($result)['total'];

    // Reservas pendientes
    $query = "SELECT COUNT(*) as total FROM reservas WHERE estado = 'Pendiente'";
    $result = $baseDatos->efectuarConsulta($query);
    $estadisticas['reservas_pendientes'] = mysqli_fetch_assoc($result)['total'];
    
    return $estadisticas;
}

/**
 * Obtiene los libros mas prestados
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @param int $limite - Numero de libros a obtener
 * @return array - Array con los libros mas prestados
 */
function obtenerLibrosPopulares($baseDatos, $limite = 10) {
    $query = "SELECT l.titulo, l.autor, COUNT(p.id) as total_prestamos
              FROM libros l
              LEFT JOIN prestamos p ON l.id = p.libro_id
              GROUP BY l.id
              ORDER BY total_prestamos DESC
              LIMIT " . intval($limite);
    
    $result = $baseDatos->efectuarConsulta($query);
    $libros_populares = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $libros_populares[] = $fila;
    }
    
    return $libros_populares;
}

/**
 * Obtiene los usuarios mas activos
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @param int $limite - Numero de usuarios a obtener
 * @return array - Array con los usuarios mas activos
 */
function obtenerUsuariosActivos($baseDatos, $limite = 10) {
    $query = "SELECT u.nombre, u.email, COUNT(p.id) as total_prestamos
              FROM usuarios u
              LEFT JOIN prestamos p ON u.id = p.usuario_id
              WHERE u.Roles = 'CLIENTE'
              GROUP BY u.id
              ORDER BY total_prestamos DESC
              LIMIT " . intval($limite);
    
    $result = $baseDatos->efectuarConsulta($query);
    $usuarios_activos = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $usuarios_activos[] = $fila;
    }
    
    return $usuarios_activos;
}

/**
 * Obtiene los prestamos por mes (ultimos meses)
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @param int $meses - Numero de meses hacia atras
 * @return array - Array con los prestamos mensuales
 */
function obtenerPrestamosMensuales($baseDatos, $meses = 6) {
    $query = "SELECT 
                DATE_FORMAT(fecha_prestamo, '%Y-%m') as mes,
                COUNT(*) as total_prestamos
              FROM prestamos
              WHERE fecha_prestamo >= DATE_SUB(CURDATE(), INTERVAL " . intval($meses) . " MONTH)
              GROUP BY DATE_FORMAT(fecha_prestamo, '%Y-%m')
              ORDER BY mes ASC";
    
    $result = $baseDatos->efectuarConsulta($query);
    $prestamos_mensuales = [];
    while ($fila = mysqli_fetch_assoc($result)) {
        $prestamos_mensuales[] = $fila;
    }
    
    return $prestamos_mensuales;
}

/**
 * Obtiene datos para el grafico de distribucion de prestamos
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @return array - Array con los datos del grafico
 */
function obtenerDatosPrestamos($baseDatos) {
    $datos = [];
    
    // Prestamos activos
    $query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['activos'] = mysqli_fetch_assoc($result)['total'];
    
    // Prestamos vencidos
    $query = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo' AND fecha_devolucion < CURDATE()";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['vencidos'] = mysqli_fetch_assoc($result)['total'];
    
    // Libros disponibles
    $query = "SELECT COUNT(*) as total FROM libros WHERE disponibilidad = 'disponible'";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['disponibles'] = mysqli_fetch_assoc($result)['total'];
    
    return $datos;
}

/**
 * Obtiene datos para el grafico de estado de reservas
 * @param MySQL $baseDatos - Conexion a la base de datos
 * @return array - Array con los datos del grafico
 */
function obtenerDatosReservas($baseDatos) {
    $datos = [];
    
    // Reservas pendientes
    $query = "SELECT COUNT(*) as total FROM reservas WHERE estado = 'Pendiente'";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['pendientes'] = mysqli_fetch_assoc($result)['total'];
    
    // Reservas aprobadas
    $query = "SELECT COUNT(*) as total FROM reservas WHERE estado = 'Aprobada'";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['aprobadas'] = mysqli_fetch_assoc($result)['total'];
    
    // Reservas rechazadas
    $query = "SELECT COUNT(*) as total FROM reservas WHERE estado = 'Rechazada'";
    $result = $baseDatos->efectuarConsulta($query);
    $datos['rechazadas'] = mysqli_fetch_assoc($result)['total'];
    
    return $datos;
}

/**
 * Genera las tarjetas de estadisticas
 * @param array $estadisticas - Array con las estadisticas
 * @return string - HTML de las tarjetas
 */
function generarTarjetasEstadisticas($estadisticas) {
    $html = '<div class="row mb-4">';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-primary text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . $estadisticas['total_usuarios'] . '</h3>';
    $html .= '<p class="mb-0">usuarios</p>';
    $html .= '</div></div></div>';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-info text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . $estadisticas['total_libros'] . '</h3>';
    $html .= '<p class="mb-0">libros</p>';
    $html .= '</div></div></div>';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-success text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . $estadisticas['prestamos_activos'] . '</h3>';
    $html .= '<p class="mb-0">prestamos activos</p>';
    $html .= '</div></div></div>';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-danger text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . $estadisticas['prestamos_vencidos'] . '</h3>';
    $html .= '<p class="mb-0">prestamos vencidos</p>';
    $html .= '</div></div></div>';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-warning text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . $estadisticas['reservas_pendientes'] . '</h3>';
    $html .= '<p class="mb-0">reservas pendientes</p>';
    $html .= '</div></div></div>';
    
    $html .= '<div class="col-md-2">';
    $html .= '<div class="card bg-secondary text-white text-center">';
    $html .= '<div class="card-body">';
    $html .= '<h3>' . ($estadisticas['prestamos_activos'] + $estadisticas['prestamos_vencidos']) . '</h3>';
    $html .= '<p class="mb-0">total prestamos</p>';
    $html .= '</div></div></div>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Genera la tabla de libros populares
 * @param array $libros - Array con los libros
 * @param int $limite - Numero de libros a mostrar
 * @return string - HTML de la tabla
 */
function generarTablaLibrosPopulares($libros, $limite = 5) {
    $html = '<div class="table-responsive">';
    $html .= '<table class="table table-sm">';
    $html .= '<thead><tr><th>libro</th><th>autor</th><th>prestamos</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach (array_slice($libros, 0, $limite) as $libro) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($libro['titulo']) . '</td>';
        $html .= '<td>' . htmlspecialchars($libro['autor']) . '</td>';
        $html .= '<td><span class="badge bg-primary">' . $libro['total_prestamos'] . '</span></td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table></div>';
    
    return $html;
}

/**
 * Genera la tabla de usuarios activos
 * @param array $usuarios - Array con los usuarios
 * @param int $limite - Numero de usuarios a mostrar
 * @return string - HTML de la tabla
 */
function generarTablaUsuariosActivos($usuarios, $limite = 5) {
    $html = '<div class="table-responsive">';
    $html .= '<table class="table table-sm">';
    $html .= '<thead><tr><th>usuario</th><th>email</th><th>prestamos</th></tr></thead>';
    $html .= '<tbody>';
    
    foreach (array_slice($usuarios, 0, $limite) as $usuario) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($usuario['nombre']) . '</td>';
        $html .= '<td>' . htmlspecialchars($usuario['email']) . '</td>';
        $html .= '<td><span class="badge bg-success">' . $usuario['total_prestamos'] . '</span></td>';
        $html .= '</tr>';
    }
    
    $html .= '</tbody></table></div>';
    
    return $html;
}
?>