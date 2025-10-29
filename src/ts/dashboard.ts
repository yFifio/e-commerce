declare const Chart: any;

interface DashboardData {
  total_animais: string;
  total_usuarios: string;
  total_adocoes: string;
  faturamento_total: string;
  total_messages: string; // Nova funcionalidade: total de mensagens de contato
  recent_animals: Array<{
    id: number;
    especie: string;
    imagem_url: string;
    data_nascimento: string;
  }>; // Nova funcionalidade: animais recentes
  recent_adoptions: Array<{
    id: number;
    data_adocao: string;
    valor_total: string;
    usuario_nome: string;
  }>; // Nova funcionalidade: adoções recentes
}

let myChart: any = null; // Variável para armazenar a instância do gráfico

async function fetchDashboardData(
  period: string = "all_time"
): Promise<DashboardData> {
  const response = await fetch(`/dashboard/data?period=${period}`);
  if (!response.ok) {
    throw new Error("Falha ao buscar dados do dashboard");
  }
  return await response.json();
}

function updateSummaryCards(data: DashboardData): void {
  document.getElementById("total-animais")!.textContent =
    data.total_animais || "0";
  document.getElementById("total-usuarios")!.textContent =
    data.total_usuarios || "0";
  document.getElementById("total-adocoes")!.textContent =
    data.total_adocoes || "0";
  const faturamento = parseFloat(data.faturamento_total ?? "0").toLocaleString(
    "pt-BR",
    { style: "currency", currency: "BRL" }
  );
  document.getElementById("faturamento-total")!.textContent = faturamento;
  document.getElementById("total-messages")!.textContent =
    data.total_messages || "0";
}

function populateRecentAnimals(
  animals: Array<{
    id: number;
    especie: string;
    imagem_url: string;
    data_nascimento: string;
  }>
): void {
  const list = document.getElementById("recent-animals-list");
  if (!list) return;

  list.innerHTML = ""; // Limpa itens existentes
  if (animals.length === 0) {
    list.innerHTML =
      '<li class="list-group-item text-muted">Nenhum animal adicionado recentemente.</li>';
    return;
  }

  animals.forEach((animal) => {
    const listItem = document.createElement("li");
    listItem.className =
      "list-group-item d-flex justify-content-between align-items-center";
    const imageUrl = animal.imagem_url
      ? `/imagem/${animal.imagem_url}`
      : "https://via.placeholder.com/50x50.png?text=No+Img";
    const date = new Date(animal.data_nascimento).toLocaleDateString("pt-BR");
    listItem.innerHTML = `
      <div class="d-flex align-items-center">
        <img src="${imageUrl}" alt="${animal.especie}" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
        <a href="/index.php/animal?id=${animal.id}">${animal.especie}</a>
      </div>
      <small class="text-muted">${date}</small>
    `;
    list.appendChild(listItem);
  });
}

function populateRecentAdoptions(
  adoptions: Array<{
    id: number;
    data_adocao: string;
    valor_total: string;
    usuario_nome: string;
  }>
): void {
  const list = document.getElementById("recent-adoptions-list");
  if (!list) return;

  list.innerHTML = ""; // Limpa itens existentes
  if (adoptions.length === 0) {
    list.innerHTML =
      '<li class="list-group-item text-muted">Nenhuma adoção recente.</li>';
    return;
  }

  adoptions.forEach((adoption) => {
    const listItem = document.createElement("li");
    listItem.className =
      "list-group-item d-flex justify-content-between align-items-center";
    const date = new Date(adoption.data_adocao).toLocaleDateString("pt-BR");
    const total = parseFloat(adoption.valor_total).toLocaleString("pt-BR", {
      style: "currency",
      currency: "BRL",
    });
    listItem.innerHTML = `
      <div>
        Adoção #${adoption.id} por <strong>${adoption.usuario_nome}</strong>
      </div>
      <small class="text-muted">${total} em ${date}</small>
    `;
    list.appendChild(listItem);
  });
}

async function loadDashboard(period: string = "all_time") {
  try {
    const data = await fetchDashboardData(period);
    updateSummaryCards(data);
    populateRecentAnimals(data.recent_animals); // Popula a nova seção de animais
    populateRecentAdoptions(data.recent_adoptions); // Popula a nova seção de adoções

    if (myChart) {
      myChart.destroy(); // Destrói o gráfico antigo antes de criar um novo
    }

    const canvas = document.getElementById("myChart") as HTMLCanvasElement;
    const ctx = canvas?.getContext("2d");

    if (ctx) {
      myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["Animais", "Usuários", "Adoções"],
          datasets: [
            {
              data: [
                parseInt(data.total_animais, 10),
                parseInt(data.total_usuarios, 10),
                parseInt(data.total_adocoes, 10),
              ],
              backgroundColor: [
                "rgb(54, 162, 235)",
                "rgb(75, 192, 192)",
                "rgb(255, 206, 86)",
              ],
              hoverOffset: 4,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: {
            animateScale: true,
            animateRotate: true,
          },
          plugins: {
            legend: {
              display: false, // Legenda será customizada ou implícita na lista ao lado
            },
            title: {
              display: true,
              text: "Visão Geral do E-commerce",
              font: {
                size: 18,
              },
            },
          },
        },
      });
    }
  } catch (error) {
    console.error("Erro ao carregar o dashboard:", error);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const periodFilter = document.getElementById(
    "period-filter"
  ) as HTMLSelectElement;

  // Carrega o dashboard inicial
  loadDashboard(periodFilter.value);

  // Adiciona o listener para mudanças no filtro
  periodFilter.addEventListener("change", () =>
    loadDashboard(periodFilter.value)
  );
});
