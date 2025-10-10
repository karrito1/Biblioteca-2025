function mostrarGraficoCargos() {
  fetch("../datos_cargos.php")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.cargo);
      const valores = data.map((item) => item.total_empleados);

      const ctx = document.getElementById("graficoCargos").getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Empleados por Cargo",
              data: valores,
              backgroundColor: "rgba(153, 102, 255, 0.5)",
              borderColor: "rgba(153, 102, 255, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: { scales: { y: { beginAtZero: true } } },
      });
    })
    .catch((error) => console.error("Error al cargar los datos:", error));
}
