"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
function fetchDashboardData() {
    return __awaiter(this, void 0, void 0, function* () {
        const response = yield fetch("/dashboard/data");
        if (!response.ok) {
            throw new Error("Falha ao buscar dados do dashboard");
        }
        return yield response.json();
    });
}
function updateSummaryCards(data) {
    var _a;
    document.getElementById("total-animais").textContent =
        data.total_animais || "0";
    document.getElementById("total-usuarios").textContent =
        data.total_usuarios || "0";
    document.getElementById("total-adocoes").textContent =
        data.total_adocoes || "0";
    const faturamento = parseFloat((_a = data.faturamento_total) !== null && _a !== void 0 ? _a : "0").toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
    document.getElementById("faturamento-total").textContent = faturamento;
}
(() => __awaiter(void 0, void 0, void 0, function* () {
    var _a;
    try {
        const data = yield fetchDashboardData();
        updateSummaryCards(data);
        const ctx = (_a = document.getElementById("myChart")) === null || _a === void 0 ? void 0 : _a.getContext("2d");
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
    }
    catch (error) {
        console.error("Erro ao carregar o dashboard:", error);
    }
}))();
