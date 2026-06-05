<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Desbravadores</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

    <style>
        :root {
            --color-bg: #f0f2f7;
            --color-surface: #ffffff;
            --color-primary: #2563eb;
            --color-success: #16a34a;
            --color-info: #0891b2;
            --color-warning: #d97706;
            --color-danger: #dc2626;
            --color-text: #1e293b;
            --color-muted: #64748b;
            --radius: 18px;
            --shadow: 0 4px 24px rgba(0,0,0,0.07);
            --shadow-hover: 0 12px 32px rgba(0,0,0,0.13);
        }

        * { box-sizing: border-box; }

        body {
            background: var(--color-bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--color-text);
            padding-top: 80px;
        }

        /* Navbar */
        .navbar-custom {
            background: var(--color-surface);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 2rem;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .navbar-brand-text {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            color: var(--color-primary);
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .navbar-links a {
            color: var(--color-muted);
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.92rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar-links a:hover { color: var(--color-primary); }

        /* Header */
        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            color: var(--color-text);
            letter-spacing: -1px;
            line-height: 1.1;
        }

        /* KPI Cards */
        .kpi-card {
            background: var(--color-surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }
        .kpi-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .kpi-value {
            font-family: 'Syne', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
        }
        .kpi-label { font-size: 0.85rem; color: var(--color-muted); font-weight: 500; margin-bottom: 0.25rem; }
        .kpi-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 0.78rem; font-weight: 600;
            padding: 3px 8px; border-radius: 20px;
            margin-top: 4px;
        }
        .kpi-badge.up { background: #dcfce7; color: #15803d; }
        .kpi-badge.neutral { background: #f1f5f9; color: var(--color-muted); }
        .kpi-badge.star { background: #fef9c3; color: #92400e; }

        /* Chart cards */
        .chart-card {
            background: var(--color-surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.5rem;
            height: 100%;
        }
        .chart-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--color-text);
        }
        .chart-wrapper { position: relative; height: 300px; }

        /* Lists */
        .highlight-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            background: #f8fafc;
            transition: background 0.2s;
        }
        .highlight-item:hover { background: #f1f5f9; }
        .highlight-item strong { font-size: 0.95rem; }
        .highlight-pct {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .alert-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            background: #fff7ed;
            transition: background 0.2s;
        }
        .alert-item:hover { background: #ffedd5; }

        .class-badge {
            font-size: 0.72rem;
            padding: 3px 9px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-primary-custom:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-outline-custom {
            background: transparent;
            color: var(--color-primary);
            border: 1.5px solid var(--color-primary);
            border-radius: 10px;
            padding: 0.5rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-outline-custom:hover { background: #eff6ff; transform: translateY(-1px); }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 1rem;
        }

        /* Fade-in animation */
        .fade-in {
            animation: fadeIn 0.5s ease both;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in:nth-child(1) { animation-delay: 0.0s; }
        .fade-in:nth-child(2) { animation-delay: 0.08s; }
        .fade-in:nth-child(3) { animation-delay: 0.16s; }
        .fade-in:nth-child(4) { animation-delay: 0.24s; }
    </style>
</head>
<body>

<?php
// Barra de navegação PHP mantida para compatibilidade
require_once('./application/views/componentes/barra_de_navegacao.php');
?>

<!-- Navbar simples (Vue) -->
<div id="navbar-app">
    <nav class="navbar-custom">
        <span class="navbar-brand-text"><i class="fas fa-compass me-2"></i>Desbravadores</span>
        <div class="navbar-links">
            <a href="#"><i class="fas fa-home me-1"></i>Início</a>
            <a href="#"><i class="fas fa-users me-1"></i>Membros</a>
            <a href="#"><i class="fas fa-book me-1"></i>Classes</a>
            <a href="#"><i class="fas fa-cog me-1"></i>Config.</a>
        </div>
    </nav>
</div>

<!-- App Vue Principal -->
<div id="app">
    <div class="container-fluid px-4 py-3">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start mb-4 page-header">
            <div>
                <h1><i class="fas fa-chart-line text-primary me-2"></i>Dashboard Desbravadores</h1>
                <p style="color:var(--color-muted); font-size:0.9rem;">Visão estratégica • {{ mesAtual }}</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-outline-custom" @click="exportar">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <button class="btn-primary-custom" @click="novoProgresso">
                    <i class="fas fa-plus"></i> Novo Progresso
                </button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6 fade-in" v-for="kpi in kpis" :key="kpi.label">
                <div class="kpi-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="kpi-label">{{ kpi.label }}</p>
                            <div class="kpi-value" :style="{ color: kpi.color }">{{ kpi.valor }}</div>
                            <span class="kpi-badge" :class="kpi.badgeType">
                                <i :class="kpi.badgeIcon"></i> {{ kpi.badgeText }}
                            </span>
                        </div>
                        <div class="kpi-icon" :style="{ background: kpi.iconBg }">
                            <i :class="kpi.icon" :style="{ color: kpi.color }"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="chart-card-title">
                        <i class="fas fa-chart-line text-primary me-2"></i>Evolução do Progresso Geral
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="evolucaoChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-card-title">
                        <i class="fas fa-chart-pie text-info me-2"></i>Alunos por Classe
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="donutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destaques + Atenção -->
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="chart-card">
                    <div class="section-title"><i class="fas fa-star text-warning me-2"></i>Destaques do Mês</div>
                    <div class="highlight-item" v-for="d in destaques" :key="d.nome">
                        <div>
                            <strong>{{ d.nome }}</strong>
                            <span class="class-badge" :style="{ background: d.badgeBg, color: d.badgeColor }">{{ d.classe }}</span>
                        </div>
                        <span class="highlight-pct" style="color: var(--color-success)">{{ d.pct }}%</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="chart-card" style="border: 2px solid #fcd34d;">
                    <div class="section-title" style="color: var(--color-warning);">
                        <i class="fas fa-exclamation-triangle me-2"></i>Atenção — Atrasados
                    </div>
                    <div class="alert-item" v-for="a in atrasados" :key="a.nome">
                        <div>
                            <strong>{{ a.nome }}</strong>
                            <small style="color:var(--color-muted); margin-left:6px;">({{ a.classe }})</small>
                        </div>
                        <span class="class-badge" :style="{ background: a.badgeBg, color: '#fff', fontSize: '0.85rem', padding: '4px 10px' }">
                            {{ a.pct }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const { createApp, onMounted } = Vue;

createApp({
    data() {
        return {
            mesAtual: 'Junho 2026',

            kpis: [
                {
                    label: 'Total de Desbravadores',
                    valor: '248',
                    color: '#2563eb',
                    iconBg: '#eff6ff',
                    icon: 'fas fa-users',
                    badgeType: 'up',
                    badgeIcon: 'fas fa-arrow-up',
                    badgeText: '+12 este mês'
                },
                {
                    label: 'Média de Progresso',
                    valor: '76%',
                    color: '#16a34a',
                    iconBg: '#f0fdf4',
                    icon: 'fas fa-chart-pie',
                    badgeType: 'up',
                    badgeIcon: 'fas fa-arrow-up',
                    badgeText: '+4% desde maio'
                },
                {
                    label: 'Classes Ativas',
                    valor: '7',
                    color: '#0891b2',
                    iconBg: '#ecfeff',
                    icon: 'fas fa-layer-group',
                    badgeType: 'neutral',
                    badgeIcon: 'fas fa-info-circle',
                    badgeText: 'de 9 classes'
                },
                {
                    label: 'Unidade em Destaque',
                    valor: 'Alfa',
                    color: '#d97706',
                    iconBg: '#fffbeb',
                    icon: 'fas fa-trophy',
                    badgeType: 'star',
                    badgeIcon: 'fas fa-star',
                    badgeText: '82% de média'
                }
            ],

            destaques: [
                { nome: 'Ana Clara Mendes', classe: 'Companheiro', pct: 98, badgeBg: '#dcfce7', badgeColor: '#15803d' },
                { nome: 'Lucas Oliveira',   classe: 'Pesquisador',  pct: 95, badgeBg: '#dbeafe', badgeColor: '#1d4ed8' },
                { nome: 'Sofia Costa',      classe: 'Guia',         pct: 93, badgeBg: '#cffafe', badgeColor: '#0e7490' }
            ],

            atrasados: [
                { nome: 'Miguel Santos',    classe: 'Amigo',       pct: 28, badgeBg: '#dc2626' },
                { nome: 'Isabela Ferreira', classe: 'Companheiro', pct: 41, badgeBg: '#d97706' }
            ]
        };
    },

    methods: {
        exportar() {
            alert('Funcionalidade de exportação em desenvolvimento.');
        },
        novoProgresso() {
            alert('Redirecionando para novo progresso...');
        },
        initCharts() {
            // Gráfico de Linha
            const ctx1 = document.getElementById('evolucaoChart');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                    datasets: [{
                        label: 'Progresso Médio (%)',
                        data: [62, 68, 71, 74, 73, 76],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.08)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 5,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: {
                            min: 50, max: 100,
                            ticks: { callback: v => v + '%' },
                            grid: { color: '#f1f5f9' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Gráfico Donut
            const ctx2 = document.getElementById('donutChart');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Amigo', 'Companheiro', 'Pesquisador', 'Pioneiro', 'Guia'],
                    datasets: [{
                        data: [45, 68, 52, 38, 45],
                        backgroundColor: ['#2563eb', '#16a34a', '#0891b2', '#d97706', '#7c3aed'],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 12, font: { size: 12 } }
                        }
                    }
                }
            });
        }
    },

    mounted() {
        this.$nextTick(() => {
            this.initCharts();
        });
    }

}).mount('#app');
</script>

</body>
</html>