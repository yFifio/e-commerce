document.addEventListener("DOMContentLoaded", function () {
  const periodFilter = document.getElementById("period-filter");
  const datepickerButton = document.getElementById("datepicker-button");
  const chartCanvas = document.getElementById("myChart");

  const ctx = chartCanvas ? chartCanvas.getContext("2d") : null; // Obtém o contexto de forma segura
  let datepickerLabel = datepickerButton
    ? datepickerButton.querySelector("span")
    : null;
  let selectedStartDate = null,
    selectedEndDate = null;
  let myChart = null;

  const formatCurrency = (value) => {
    return parseFloat(value).toLocaleString("pt-BR", {
      style: "currency",
      currency: "BRL",
    });
  };

  const fetchDataAndUpdateDashboard = async (
    period = "all_time",
    startDate = null,
    endDate = null
  ) => {
    try {
      const params = new URLSearchParams({ period });
      if (startDate && endDate) {
        params.set("start_date", startDate);
        params.set("end_date", endDate);
      }
      const response = await fetch(`/dashboard/data?${params.toString()}`);
      if (!response.ok) {
        throw new Error("Falha ao buscar dados do dashboard.");
      }
      const data = await response.json();

      document.getElementById("faturamento-total").textContent = formatCurrency(
        data.faturamento_total
      );
      document.getElementById("total-adocoes").textContent = data.total_adocoes;
      document.getElementById("total-animais").textContent = data.total_animais;
      document.getElementById("total-usuarios").textContent =
        data.total_usuarios;
      document.getElementById("total-messages").textContent =
        data.total_messages;

      const adoptionsList = document.getElementById("recent-adoptions-list");
      adoptionsList.innerHTML = "";
      if (data.recent_adoptions && data.recent_adoptions.length > 0) {
        data.recent_adoptions.forEach((adocao) => {
          const li = document.createElement("li");
          li.className =
            "list-group-item d-flex justify-content-between align-items-center";
          li.innerHTML = `
                        <div>
                            <i class="fas fa-user text-muted me-2"></i>
                            <span class="fw-bold">${adocao.usuario_nome}</span>
                            <small class="text-muted d-block">em ${new Date(
                              adocao.data_adocao
                            ).toLocaleDateString("pt-BR")}</small>
                        </div>
                        <span class="badge bg-success rounded-pill">${formatCurrency(
                          adocao.valor_total
                        )}</span>
                    `;
          adoptionsList.appendChild(li);
        });
      } else {
        adoptionsList.innerHTML =
          '<li class="list-group-item text-muted">Nenhuma adoção recente no período.</li>';
      }

      const animalsList = document.getElementById("recent-animals-list");
      animalsList.innerHTML = "";
      if (data.recent_animals && data.recent_animals.length > 0) {
        data.recent_animals.forEach((animal) => {
          const li = document.createElement("li");
          li.className = "list-group-item";
          li.innerHTML = `
                        <a href="/animal?id=${
                          animal.id
                        }" class="text-decoration-none text-dark d-flex align-items-center">
                            <img src="/imagem/${animal.imagem_url}" alt="${
            animal.especie
          }" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <div>
                                <span class="fw-bold">${animal.especie}</span>
                                <small class="text-muted d-block">Nasc.: ${new Date(
                                  animal.data_nascimento
                                ).toLocaleDateString("pt-BR")}</small>
                            </div>
                        </a>
                    `;
          animalsList.appendChild(li);
        });
      } else {
        animalsList.innerHTML =
          '<li class="list-group-item text-muted">Nenhum animal adicionado recentemente.</li>';
      }

      const chartData = {
        labels: ["Adoções", "Animais Disponíveis", "Usuários"],
        datasets: [
          {
            label: "Visão Geral",
            data: [data.total_adocoes, data.total_animais, data.total_usuarios],
            backgroundColor: [
              "rgba(0, 158, 73, 0.8)",
              "rgba(252, 218, 13, 0.8)",
              "rgba(206, 17, 38, 0.8)",
            ],
            borderColor: ["#009E49", "#FCDA0D", "#CE1126"],
            borderWidth: 1,
          },
        ],
      };

      if (myChart) {
        myChart.data = chartData;
        myChart.update();
      } else {
        myChart = new Chart(ctx, {
          type: "doughnut",
          data: chartData,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: "top",
              },
            },
          },
        });
      }
    } catch (error) {
      console.error("Erro no dashboard:", error);
      chartCanvas.parentElement.innerHTML =
        '<div class="alert alert-danger">Não foi possível carregar os dados do gráfico.</div>';
    }
  };

  /**
   * Inicializa o seletor de datas (Litepicker) de forma robusta,
   * aguardando a biblioteca ser carregada.
   */
  const initializeDatePicker = () => {
    if (!datepickerButton) return;

    // Verifica se a biblioteca Litepicker já foi carregada.
    if (typeof Litepicker === "undefined") {
      // Se não, tenta novamente em 100ms.
      setTimeout(initializeDatePicker, 100);
      return;
    }

    // Previne reinicialização se já houver uma instância.
    if (datepickerButton.hasOwnProperty("_litepicker")) {
      return;
    }

    const picker = new Litepicker({
      element: datepickerButton,
      singleMode: false,
      format: "DD/MM/YYYY",
      lang: "pt-BR",
      numberOfMonths: 1,
      tooltipText: { one: "dia", other: "dias" },
      buttonText: {
        previousMonth: `<i class="fas fa-chevron-left"></i>`,
        nextMonth: `<i class="fas fa-chevron-right"></i>`,
        reset: `<i class="fas fa-undo"></i>`,
        apply: "Aplicar",
      },
      setup: (picker) => {
        picker.on("selected", (date1, date2) => {
          selectedStartDate = date1.format("YYYY-MM-DD");
          selectedEndDate = date2.format("YYYY-MM-DD");
          periodFilter.value = "all_time";
          if (datepickerLabel) {
            datepickerLabel.textContent = `${date1.format(
              "DD/MM"
            )} - ${date2.format("DD/MM/YY")}`;
          }
          fetchDataAndUpdateDashboard(
            "all_time",
            selectedStartDate,
            selectedEndDate
          );
        });
      },
    });
    datepickerButton._litepicker = picker;
  };

  periodFilter.addEventListener("change", (e) => {
    selectedStartDate = null;
    selectedEndDate = null;
    if (datepickerLabel) {
      datepickerLabel.textContent = "Selecionar Período";
    }
    fetchDataAndUpdateDashboard(e.target.value);
  });

  initializeDatePicker();
  fetchDataAndUpdateDashboard();
});
