declare const Chart: any;

interface DashboardData {
  total_animais: string;
  total_usuarios: string;
  total_adocoes: string;
  faturamento_total: string;
}

async function fetchDashboardData(): Promise<DashboardData> {
  const response = await fetch("/dashboard/data");
  if (!response.ok) {
    throw new Error("Falha ao buscar dados do dashboard");
  }
  return await response.json();
}

function updateSummaryCards(data: DashboardData): void {
  document.getElementById("total-animais")!.textContent = data.total_animais;
  document.getElementById("total-usuarios")!.textContent = data.total_usuarios;
  document.getElementById("total-adocoes")!.textContent = data.total_adocoes;
  const faturamento = parseFloat(data.faturamento_total || "0").toLocaleString(
    "pt-BR",
    { style: "currency", currency: "BRL" }
  );
  document.getElementById("faturamento-total")!.textContent = faturamento;
}

(async () => {
  try {
    const data = await fetchDashboardData();
    updateSummaryCards(data);

    const ctx = (
      document.getElementById("myChart") as HTMLCanvasElement
    )?.getContext("2d");

    if (ctx) {
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Animais", "Usuários", "Adoções"],
          datasets: [
            {
              label: "Totais do E-commerce",
              data: [
                data.total_animais,
                data.total_usuarios,
                data.total_adocoes,
              ],
              backgroundColor: [
                "rgba(54, 162, 235, 0.5)",
                "rgba(75, 192, 192, 0.5)",
                "rgba(255, 206, 86, 0.5)",
              ],
              borderColor: [
                "rgba(54, 162, 235, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(255, 206, 86, 1)",
              ],
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            legend: {
              display: false,
            },
          },
        },
      });
    }
  } catch (error) {
    console.error("Erro ao carregar o dashboard:", error);
  }
})();
