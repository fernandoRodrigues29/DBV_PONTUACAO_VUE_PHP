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
        /*switch toggle*/
        .switch {
            width: 60px;
            height: 30px;
            background: #ccc;
            border-radius: 30px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
            }

            .switch.active {
            background: #4CAF50;
            }

            .circle {
            width: 26px;
            height: 26px;
            background: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: 0.3s;
            }

            .switch.active .circle {
            transform: translateX(30px);
            }
        /*switch toggle*/


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
                    <a class="nav-link active" href="<?= site_url('cantinho') ?>">
                        <i class="fas fa-clipboard-check"></i> Cantinho da Unidade
                    </a>
                </li>
                <li class="nav-item active">
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
 
        <listagem-cantinho
            v-if="!telaForm"
            @editar="abrirFormulario($event)" 
            @excluir="confirmarExclusao($event)" 
            :listar="listarCantinho">
        </listagem-cantinho>
        <!-- Tela de Formulário -->
        <form-cantinho
            v-if="telaForm"
            :unidade="formData"
            :lista_unidades="listarUnidades"
            :salvando="salvando"
            :lista_classes="classe_base_lista"
            :lista_desbravadores="listar_desbravadores"
            @voltar="voltar($event)"
            @salvar="salvar($event)"
        >
        </form-cantinho>
       
<transition name="slide" mode="out-in">
</transition>
    <!-- Alertas Globais -->
    <transition name="fade">
        <div v-if="false" 
             :class="'alert alert-' + alerta.tipo + ' alert-dismissible fade show'"
             style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                VARIAVEL
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

<!-- Notyf CSS + JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

<script type="module">

import ListagemCantinho from '/application/views/cantinho/Listagem_cantinho.js';
import formCantinho from '/application/views/cantinho/Formulario_comp.js';

// import Componente_alfa from '/application/views/unidades/Componente_alfa.vue';
const { createApp } = Vue;
//componente de listar unidade
// App Principal
createApp({
    components: {
        'listagem-cantinho': ListagemCantinho,
        'form-cantinho': formCantinho
    },
    data() {
        return {
            nome: ' ',
            listarCantinho:[],
            listarUnidades:[],
            formData:{},
            salvando:false,
            telaForm:false,
            classe_base_lista:['amigo/companheiro','pesquisador/pioneiro','guia/excurscionista'],
            listar_desbravadores:[]
        };
    },
    methods: {
        async carregarDados() {
           try {
                const resposta = await fetch('<?= site_url('cantinho/listar_json') ?>');
                this.listarCantinho = await resposta.json();

                const resposta2 = await fetch('<?= site_url('unidades/listar_json') ?>');
                this.listarUnidades = await resposta2.json();

                const respListarDbv = await fetch('<?=  site_url('desbravadores/api_dados') ?>?>');

                const dadosFiltrados = await respListarDbv.json();
                 this.listar_desbravadores  = dadosFiltrados.data.map(item => ({
                        id_desbravador: item.id_desbravador,
                        nome_completo: item.nome_completo,
                        id_unidade: item.id_unidade
                    }));

           } catch (error) {
            console.error('Error [carregar dados]', error);
           }
        },
        abrirFormulario(obj=null){
            if(obj){
                this.formData = {...obj};
            }
            this.telaForm = true;
        },
        voltar(){
            this.telaForm = false;
        },
        async salvar(dados, origem=null){
            console.log(dados);
            
            const url = dados.id_cantinho ? '<?= site_url('cantinho/atualizar') ?>' 
               : '<?= site_url('cantinho/inserir') ?>';
          
          const verbo = dados.id_cantinho ? 'PUT' : 'POST';
            console.log(url,verbo);
            try {
                    const resp = await fetch(url,{
                        method: verbo,
                        headers:{
                            'Content-Type':'application/json'
                        },
                        body: JSON.stringify(dados)
                    });

                    const resultado = await resp.json();
                        if(resultado.sucesso){
                            // alert(resultado.mensagem);
                            this.notyf.success(resultado.mensagem);
                            this.carregarDados();
                                this.voltar();
                        }else{
                            this.notyf.error(resultado.mensagem);
                            this.voltar();
                        }


            } catch (error) {
                this.notyf.error('problema interno do sistema, analise os logs de erro');
                console.error('error:',error);
            }
        },
        async confirmarExclusao(obj){
            if(obj){
                if(confirm(`Tem certeza que dejeza excluir #${obj.id}`)){
                    try {
                        const url = '<?= site_url('cantinho/deletar') ?>';
                        const resp = await fetch(url,{
                        method: 'DELETE',
                        headers:{
                            'Content-Type':'application/json'
                        },
                        body: JSON.stringify(obj)
                    });
                        const resultado = await resp.json();
                        console.info('dados enviados',resultado);
                        if(resultado.sucesso){
                            this.carregarDados();

                            this.notyf.success(resultado.mensagem);
                        }else{
                            alert(resultado.mensagem);
                            this.notyf.error(resultado.mensagem);
                        }
                    } catch (error) {
                        console.error('[error]',error);
                        this.notyf.error(resultado.mensagem);
                    }
                }
            }
        }
        
       
    },
    mounted() {
        this.carregarDados();
    },
    created(){
        this.notyf = new Notyf({
          duration: 4000,           // tempo em ms
          ripple: true,             // efeito de ripple
          dismissible: true,        // pode clicar para fechar
          position: { x: 'right', y: 'top' }, // canto superior direito
          
          types: [
            {
              type: 'success',
              background: '#28a745',
              className: 'notyf__toast--success'
            },
            {
              type: 'error',
              background: '#dc3545',
              className: 'notyf__toast--error'
            },
            {
              type: 'info',
              background: '#17a2b8',
              icon: {
                className: 'notyf__icon--info',
                tagName: 'i',
                text: 'i'  // ou use material icons, font-awesome, etc.
              }
            }
          ]
        });
    }

}).mount('#app');
</script>

</body>
</html>