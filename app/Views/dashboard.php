<?php require __DIR__ . '/layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard de Indicadores</h2>
    <div>
        <a href="/index.php/admin/animais/novo" class="btn btn-success">Adicionar Novo Animal</a>
        <a href="/admin/contato" class="btn btn-info">Ver Mensagens de Contato</a>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <canvas id="myChart"></canvas>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resumo Geral</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total de Animais
                        <span id="total-animais" class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total de Usuários
                        <span id="total-usuarios" class="badge bg-info rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total de Adoções
                        <span id="total-adocoes" class="badge bg-warning rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Faturamento Total
                        <span id="faturamento-total" class="badge bg-success rounded-pill">R$ 0,00</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/dashboard.js"></script>

<?php require __DIR__ . '/layouts/footer.php'; ?>