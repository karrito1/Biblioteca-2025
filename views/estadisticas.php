<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de empleados</title>
    <!-- Agregar Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6JxMU1fexFz2NRNn1qpmoiU96n6/pK2QH5A5M1A5hllJJHe2cK00Jmj3dX" crossorigin="anonymous">
</head>

<body class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Reporte de empleados</h2>

  
      

        <div id="estadisticas" class="d-none">
            <canvas id="graficoempleados" width="600" height="400" class="mb-4"></canvas>
            <canvas id="graficoCargos" width="600" height="400"></canvas>
        </div>
    </div>


    <script src="../public/js/grafico_empleados.js"></script>
    <script src="../public/js/grafico_cargos.js"></script>
    <!-- Agregar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-bqDOhzUJblQwhJ7LVhFhEtt9NNkPzVwr1F5HtqfIbsRRhTLk40uB5ua5RAn2Gdeu" crossorigin="anonymous"></script>
    <script>
        document.getElementById("btnMostrarEstadisticas").addEventListener("click", function() {
            const estadisticas = document.getElementById("estadisticas");
            if (estadisticas.classList.contains("d-none")) {
                estadisticas.classList.remove("d-none");
                estadisticas.classList.add("d-block");
                mostrarGraficoEmpleados();
                mostrarGraficoCargos();
            } else {
                estadisticas.classList.add("d-none");
                estadisticas.classList.remove("d-block");
            }
        });
    </script>
</body>

</html>