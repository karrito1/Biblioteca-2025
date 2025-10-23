// Script para manejo del modal combinado de reservas y préstamos
document.addEventListener("DOMContentLoaded", () => {
    const formReserva = document.querySelector("#formNuevaReserva");
    const formPrestamo = document.querySelector("#formNuevoPrestamo");

    // Cargar libros disponibles al abrir el modal
    const modalReservasPrestamos = document.getElementById('modalReservasPrestamos');
    modalReservasPrestamos?.addEventListener('shown.bs.modal', function() {
        cargarLibrosDisponibles();
        cargarDatos();
    });

    // Manejo del formulario de nueva reserva
    formReserva?.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: "¿Crear reserva?",
            text: "Se registrará una nueva reserva",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, crear",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#ffc107",
            cancelButtonColor: "#6c757d",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("/Biblioteca-2025/controllers/gestionReservas.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "¡Reserva creada!",
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            formReserva.reset();
                            cargarDatos();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error inesperado",
                            text: "No se pudo procesar la reserva",
                        });
                    });
            }
        });
    });

    // Manejo del formulario de nuevo préstamo
    formPrestamo?.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: "¿Registrar préstamo?",
            text: "Se registrará un nuevo préstamo",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, registrar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#007bff",
            cancelButtonColor: "#6c757d",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("/Biblioteca-2025/controllers/gestionPrestamos.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "¡Préstamo registrado!",
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            formPrestamo.reset();
                            cargarDatos();
                            cargarLibrosDisponibles(); // Actualizar lista de libros disponibles
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        Swal.fire({
                            icon: "error",
                            title: "Error inesperado",
                            text: "No se pudo procesar el préstamo",
                        });
                    });
            }
        });
    });
});

// Función para cargar libros disponibles en los selects
function cargarLibrosDisponibles() {
    // Cargar para reservas
    fetch("/Biblioteca-2025/controllers/gestionReservas.php", {
        method: "GET"
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            const selectReserva = document.getElementById('libro_reserva');
            if (selectReserva) {
                selectReserva.innerHTML = '<option value="">Seleccione un libro...</option>';
                data.data.forEach(libro => {
                    const option = document.createElement('option');
                    option.value = libro.id;
                    option.textContent = `${libro.titulo} - ${libro.autor}`;
                    selectReserva.appendChild(option);
                });
            }
        }
    })
    .catch(error => console.error("Error cargando libros para reservas:", error));

    // Cargar para préstamos
    fetch("/Biblioteca-2025/controllers/gestionPrestamos.php", {
        method: "GET"
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            const selectPrestamo = document.getElementById('libro_prestamo');
            if (selectPrestamo) {
                selectPrestamo.innerHTML = '<option value="">Seleccione un libro...</option>';
                data.data.forEach(libro => {
                    const option = document.createElement('option');
                    option.value = libro.id;
                    option.textContent = `${libro.titulo} - ${libro.autor} (${libro.cantidad} disponibles)`;
                    selectPrestamo.appendChild(option);
                });
            }
        }
    })
    .catch(error => console.error("Error cargando libros para préstamos:", error));
}

// Función para cargar datos actuales (reservas y préstamos)
function cargarDatos() {
    cargarReservas();
    cargarPrestamos();
}

// Función para cargar lista de reservas pendientes
function cargarReservas() {
    const formData = new FormData();
    formData.append('accion', 'listar_reservas');

    fetch("/Biblioteca-2025/controllers/gestionReservas.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const listaReservas = document.getElementById('listaReservas');
        if (listaReservas && data.status === "success") {
            if (data.data.length === 0) {
                listaReservas.innerHTML = '<p class="text-muted">No hay reservas pendientes</p>';
            } else {
                let html = '';
                data.data.forEach(reserva => {
                    html += `
                        <div class="card mb-2 border-warning">
                            <div class="card-body p-2">
                                <small class="text-muted">#${reserva.id}</small>
                                <h6 class="mb-1">${reserva.libro_titulo}</h6>
                                <p class="mb-1 small"><strong>${reserva.usuario_nombre}</strong></p>
                                <p class="mb-1 small text-muted">${reserva.email}</p>
                                <p class="mb-2 small">Fecha: ${reserva.fecha_reserva}</p>
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-success" onclick="gestionarReserva(${reserva.id}, 'aprobar')">
                                        <i class="zmdi zmdi-check"></i> Aprobar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="gestionarReserva(${reserva.id}, 'rechazar')">
                                        <i class="zmdi zmdi-close"></i> Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                listaReservas.innerHTML = html;
            }
        }
    })
    .catch(error => {
        console.error("Error cargando reservas:", error);
        document.getElementById('listaReservas').innerHTML = '<p class="text-danger">Error al cargar reservas</p>';
    });
}

// Función para cargar lista de préstamos activos
function cargarPrestamos() {
    const formData = new FormData();
    formData.append('accion', 'listar_prestamos');

    fetch("/Biblioteca-2025/controllers/gestionPrestamos.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const listaPrestamos = document.getElementById('listaPrestamos');
        if (listaPrestamos && data.status === "success") {
            if (data.data.length === 0) {
                listaPrestamos.innerHTML = '<p class="text-muted">No hay préstamos activos</p>';
            } else {
                let html = '';
                data.data.forEach(prestamo => {
                    const esVencido = prestamo.dias_restantes < 0;
                    const colorCard = esVencido ? 'border-danger' : 'border-success';
                    const colorTexto = esVencido ? 'text-danger' : 'text-success';
                    
                    html += `
                        <div class="card mb-2 ${colorCard}">
                            <div class="card-body p-2">
                                <small class="text-muted">#${prestamo.id}</small>
                                <h6 class="mb-1">${prestamo.libro_titulo}</h6>
                                <p class="mb-1 small"><strong>${prestamo.usuario_nombre}</strong></p>
                                <p class="mb-1 small text-muted">${prestamo.email}</p>
                                <p class="mb-1 small">Devolución: ${prestamo.fecha_devolucion}</p>
                                <p class="mb-2 small ${colorTexto}">
                                    <strong>${prestamo.dias_restantes >= 0 ? prestamo.dias_restantes + ' días restantes' : 'VENCIDO (' + Math.abs(prestamo.dias_restantes) + ' días)'}</strong>
                                </p>
                                <button class="btn btn-sm btn-primary w-100" onclick="devolverPrestamo(${prestamo.id})">
                                    <i class="zmdi zmdi-assignment-return"></i> Devolver
                                </button>
                            </div>
                        </div>
                    `;
                });
                listaPrestamos.innerHTML = html;
            }
        }
    })
    .catch(error => {
        console.error("Error cargando préstamos:", error);
        document.getElementById('listaPrestamos').innerHTML = '<p class="text-danger">Error al cargar préstamos</p>';
    });
}

// Función para gestionar reservas (aprobar/rechazar)
function gestionarReserva(reservaId, accion) {
    const accionTexto = accion === 'aprobar' ? 'aprobar' : 'rechazar';
    const accionCapitalizada = accionTexto.charAt(0).toUpperCase() + accionTexto.slice(1);
    
    Swal.fire({
        title: `¿${accionCapitalizada} reserva?`,
        text: `¿Está seguro de ${accionTexto} esta reserva?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: `Sí, ${accionTexto}`,
        cancelButtonText: "Cancelar",
        confirmButtonColor: accion === 'aprobar' ? "#28a745" : "#dc3545",
        cancelButtonColor: "#6c757d",
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('accion', accion === 'aprobar' ? 'aprobar_reserva' : 'rechazar_reserva');
            formData.append('reserva_id', reservaId);

            fetch("/Biblioteca-2025/controllers/gestionReservas.php", {
                method: "POST",
                body: formData,
            })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "¡Reserva " + accionTexto + "da!",
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    cargarReservas();
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message,
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: "No se pudo procesar la acción",
                });
            });
        }
    });
}

// Función para devolver préstamo
function devolverPrestamo(prestamoId) {
    Swal.fire({
        title: "¿Procesar devolución?",
        text: "¿Está seguro de marcar este préstamo como devuelto?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, devolver",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#007bff",
        cancelButtonColor: "#6c757d",
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('accion', 'devolver_prestamo');
            formData.append('prestamo_id', prestamoId);

            fetch("/Biblioteca-2025/controllers/gestionPrestamos.php", {
                method: "POST",
                body: formData,
            })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "¡Préstamo devuelto!",
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                    });
                    cargarPrestamos();
                    cargarLibrosDisponibles(); // Actualizar libros disponibles
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message,
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error inesperado",
                    text: "No se pudo procesar la devolución",
                });
            });
        }
    });
}