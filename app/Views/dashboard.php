<?php require __DIR__ . '/layouts/header.php'; ?>

<style>
    /* Cores Rastafari */
    :root {
        --rasta-green: #009E49;
        --rasta-yellow: #FCDA0D;
        --rasta-red: #CE1126;
        --rasta-black: #333;
    }

    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .border-left-rasta-green { border-left: 0.25rem solid var(--rasta-green) !important; }
    .text-rasta-green { color: var(--rasta-green) !important; }

    .border-left-rasta-yellow { border-left: 0.25rem solid var(--rasta-yellow) !important; }
    .text-rasta-yellow { color: var(--rasta-yellow) !important; }

    .border-left-rasta-red { border-left: 0.25rem solid var(--rasta-red) !important; }
    .text-rasta-red { color: var(--rasta-red) !important; }

    .border-left-rasta-black { border-left: 0.25rem solid var(--rasta-black) !important; }
    .text-rasta-black { color: var(--rasta-black) !important; }

    #recent-adoptions-list .badge {
        background-color: var(--rasta-green) !important;
    }
    #total-messages {
        background-color: var(--rasta-red) !important;
        animation: pulse 2s infinite;
    }

    /* Estilo para o botão do calendário */
    #datepicker-button {
        background-color: var(--rasta-black);
        color: white;
    }

    /* Animação e Estilo do Calendário (Litepicker) */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .litepicker {
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        border: 2px solid var(--rasta-black);
        animation: fadeIn 0.3s ease-out;
    }

    .litepicker .container__months .month-item-header {
        background-color: var(--rasta-black);
        color: white;
        padding: 10px 0;
    }

    .litepicker .container__months .month-item-header .month-item-year,
    .litepicker .container__months .month-item-header .month-item-name {
        font-size: 1.1em;
    }

    .litepicker .container__months .month-item-header div,
    .litepicker .container__months .month-item-header button {
        color: white;
    }

    .litepicker .container__days > a:hover {
        background-color: var(--rasta-yellow) !important;
        color: var(--rasta-black) !important;
    }

    .litepicker .button-apply, .litepicker .button-cancel {
        transition: transform 0.2s ease;
    }
    .litepicker .button-apply:hover, .litepicker .button-cancel:hover {
        transform: scale(1.05);
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Dashboard Administrativo</h1>
        <div class="d-flex align-items-center">
            <label for="period-filter" class="form-label me-2 mb-0">Período:</label>
            <select id="period-filter" class="form-select form-select-sm me-3" style="width: auto;">
                <option value="all_time" selected>Todo o período</option>
                <option value="this_year">Este ano</option>
                <option value="last_month">Mês passado</option>
                <option value="this_month">Este mês</option>
                <option value="30_days">Últimos 30 dias</option>
                <option value="7_days">Últimos 7 dias</option>
            </select>
            <button id="datepicker-button" class="btn btn-sm">
                <i class="fas fa-calendar-alt me-1"></i> <span>Selecionar Período</span>
            </button>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-rasta-green shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-rasta-green text-uppercase mb-1">Faturamento Total</div>
                            <div id="faturamento-total" class="h5 mb-0 font-weight-bold text-gray-800">R$ 0,00</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-rasta-yellow shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-rasta-yellow text-uppercase mb-1">Adoções Realizadas</div>
                            <div id="total-adocoes" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-rasta-red shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-rasta-red text-uppercase mb-1">Animais Disponíveis</div>
                            <div id="total-animais" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-rasta-black shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-rasta-black text-uppercase mb-1">Usuários Cadastrados</div>
                            <div id="total-usuarios" class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico e Atividades Recentes -->
    <div class="row">
        <!-- Gráfico de Rosca -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-rasta-black">Visão Geral</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:300px">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-rasta-black">Ações Rápidas</h6>
                </div>
                <div class="card-body">
                    <a href="/admin/animais/novo" class="btn w-100" style="background-color: var(--rasta-green); color: white;">
                        <i class="fas fa-plus-circle me-2"></i>Adicionar Novo Animal
                    </a>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-rasta-black">Adoções Recentes</h6>
                </div>
                <div class="card-body p-0">
                    <ul id="recent-adoptions-list" class="list-group list-group-flush">
                        <li class="list-group-item text-muted">Carregando...</li>
                    </ul>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-rasta-black">Animais Adicionados Recentemente</h6>
                </div>
                <div class="card-body p-0">
                    <ul id="recent-animals-list" class="list-group list-group-flush">
                        <li class="list-group-item text-muted">Carregando...</li>
                    </ul>
                </div>
            </div>
             <div class="card shadow mb-4">
                <a href="/admin/contato" class="text-decoration-none">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="text-xs font-weight-bold text-rasta-red text-uppercase">Mensagens de Contato</div>
                        <div id="total-messages" class="badge bg-danger rounded-pill">0</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Incluindo a biblioteca Chart.js e o script do dashboard -->
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/dashboard.js"></script>

<?php require __DIR__ . '/layouts/footer.php'; ?>