function mostrarGraficoEmpleados() {
  fetch("../datos_empleados.php")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.nombreDepartamento);
      const valores = data.map((item) => item.total);

      const ctx = document.getElementById("graficoempleados").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Empleados por Departamento",
              data: valores,
              backgroundColor: "rgba(54, 162, 235, 0.5)",
              borderColor: "rgba(54, 162, 235, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true },
          },
        },
      });
    })
    .catch((error) => console.error("Error cargando datos empleados:", error));
}
