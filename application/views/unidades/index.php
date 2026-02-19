<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desbravadores - Sistema Vue</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { 
            padding-top: 70px; 
            background-color: #f8f9fa; 
        }
        .navbar-brand { 
            font-weight: bold; 
        }
        .nav-link i { 
            margin-right: 8px; 
        }
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
        .list-enter-active, .list-leave-active {
            transition: all 0.3s ease;
        }
        .list-enter-from {
            opacity: 0;
            transform: translateY(-10px);
        }
        .list-leave-to {
            opacity: 0;
            transform: translateY(10px);
        }
        .slide-enter-active, .slide-leave-active {
            transition: all 0.4s ease;
        }
        .slide-enter-from {
            transform: translateX(100%);
            opacity: 0;
        }
        .slide-leave-to {
            transform: translateX(-100%);
            opacity: 0;
        }
        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .badge-custom {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0,0,0,.1);
            border-radius: 50%;
            border-top-color: #007bff;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .btn-voltar {
            position: fixed;
            top: 80px;
            left: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('pontuacao/lancar') ?>">
            <i class="fas fa-campground"></i> Sistema Cantinho da Unidade
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('pontuacao/lancar') ?>">
                        <i class="fas fa-clipboard-check"></i> Cantinho da Unidade
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('unidades') ?>">
                        <i class="fas fa-flag"></i> Unidades
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= site_url('desbravadores') ?>">
                        <i class="fas fa-users"></i> Desbravadores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('pontuacao/ranking') ?>">
                        <i class="fas fa-trophy"></i> Ranking Geral
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="app">
<!-- Tela de Listagem -->
        <listagem-unidades></listagem-unidades>
        <!-- Tela de Formulário -->




<transition name="slide" mode="out-in">
</transition>
    <!-- Alertas Globais -->
    <transition name="fade">
        <div v-if="alerta.mensagem" 
             :class="'alert alert-' + alerta.tipo + ' alert-dismissible fade show'"
             style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
            {{ alerta.mensagem }}
            <button type="button" class="close" @click="fecharAlerta">
                <span>&times;</span>
            </button>
        </div>
    </transition>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> Sistema Cantinho da Unidade - Desbravadores</p>
        <small>Desenvolvido com CodeIgniter 3 + SQLite + Vue.js 3</small>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js"></script>
<script type="module">

import Testando from '/application/views/components/Testando.js';

const { createApp } = Vue;
//componente de listar unidade
const ListagemUnidades = {
    props:[],
    data(){
        return {
            name:"Listar Unidades"
        }
    },
    methods: {},
    template:`
        <div class="container mt-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1><i class="fas fa-users"></i> Unidades</h1>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success">
                        <i class="fas fa-plus"></i> Adicionar Unidade
                    </button>
                </div>
            </div>
            <div class="card mb-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                             <input 
                                type="text" 
                                class="form-control" 
                                placeholder="Buscar por nome...">
                        </div>
                        <div class="col-md-3">
                            <button  class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
            //lista
            <div class="row">
                 <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                             <h5 class="card-title">
                                <i class="fas fa-user-circle text-primary"></i> 
                                        VARIAVEL
                            </h5>
                            <p class="card-text">
                                <span class="badge badge-primary badge-custom">
                                    <i class="fas fa-flag"></i> VARIAVEL
                                </span>
                                        <span class="badge badge-info badge-custom ml-2">
                                            VARIAVEL
                                        </span>
                            </p>
                            <p class="card-text text-muted mb-0">
                                <small><strong>Classe:</strong> VARIAVEL</small>
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <button 
                                    class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger ml-2">
                                        <i class="fas fa-trash"></i> Excluir
                            </button>
                        </div>
                    </div>        
                 </div>
            </div>
    </div>
    `
};
// App Principal
createApp({
    components: {
        'listagem-unidades': ListagemUnidades,
        'testando': Testando
    },
    data() {
        return {
            nome: ' '
        };
    },
    methods: {
        async carregarDados() {
           
        },
    },
    mounted() {
    }
}).mount('#app');
</script>

</body>
</html>