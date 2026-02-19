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
    <transition name="slide" mode="out-in">
        <!-- Tela de Listagem -->
        <listagem-desbravadores
            v-if="telaAtual === 'listagem'"
            :desbravadores="desbravadores"
            :unidades="unidades"
            :carregando="carregando"
            @adicionar="abrirFormulario(null)"
            @editar="abrirFormulario($event)"
            @excluir="confirmarExclusao($event)"
            @alerta="mostrarAlerta($event)"
        ></listagem-desbravadores>

        <!-- Tela de Formulário -->
        <formulario-desbravador
            v-else-if="telaAtual === 'formulario'"
            :desbravador="desbravadorEdicao"
            :unidades="unidades"
            :salvando="salvando"
            @voltar="voltarListagem"
            @salvar="salvar($event)"
        ></formulario-desbravador>
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
<script>
const { createApp } = Vue;

// Componente de Listagem
const ListagemDesbravadores = {
    props: ['desbravadores', 'unidades', 'carregando'],
    data() {
        return {
            filtros: {
                busca: '',
                unidade: '',
                cargo: ''
            }
        };
    },
    computed: {
        desbravadoresFiltrados() {
            return this.desbravadores.filter(d => {
                const buscaMatch = !this.filtros.busca || 
                    d.nome_completo.toLowerCase().includes(this.filtros.busca.toLowerCase());
                
                const unidadeMatch = !this.filtros.unidade || 
                    d.id_unidade == this.filtros.unidade;
                
                const cargoMatch = !this.filtros.cargo || 
                    d.cargo === this.filtros.cargo;
                
                return buscaMatch && unidadeMatch && cargoMatch;
            });
        }
    },
    methods: {
        limparFiltros() {
            this.filtros = {
                busca: '',
                unidade: '',
                cargo: ''
            };
        }
    },
    template: `
        <div class="container mt-4">
            <!-- Cabeçalho -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1><i class="fas fa-users"></i> Desbravadores</h1>
                </div>
                <div class="col-md-6 text-right">
                    <button @click="$emit('adicionar')" class="btn btn-success">
                        <i class="fas fa-plus"></i> Adicionar Desbravador
                    </button>
                </div>
            </div>

            <!-- Filtros e Busca -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input 
                                v-model="filtros.busca" 
                                type="text" 
                                class="form-control" 
                                placeholder="Buscar por nome...">
                        </div>
                        <div class="col-md-3">
                            <select v-model="filtros.unidade" class="form-control">
                                <option value="">Todas as Unidades</option>
                                <option v-for="u in unidades" :key="u.id" :value="u.id">
                                    {{ u.nome }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select v-model="filtros.cargo" class="form-control">
                                <option value="">Todos os Cargos</option>
                                <option value="Desbravador">Desbravador</option>
                                <option value="Secretário">Secretário</option>
                                <option value="Capitão">Capitão</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button @click="limparFiltros" class="btn btn-outline-secondary btn-block">
                                <i class="fas fa-eraser"></i> Limpar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="carregando" class="text-center py-5">
                <div class="loading-spinner"></div>
                <p class="mt-2">Carregando...</p>
            </div>

            <!-- Lista de Desbravadores -->
            <div v-else>
                <div v-if="desbravadoresFiltrados.length === 0" class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhum desbravador encontrado.
                </div>

                <div v-else class="row">
                    <transition-group name="list" tag="div" class="col-12">
                        <div 
                            v-for="desb in desbravadoresFiltrados" 
                            :key="desb.id_desbravador" 
                            class="col-md-6 col-lg-4 mb-3">
                            <div class="card card-hover h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-user-circle text-primary"></i> 
                                        {{ desb.nome_completo }}
                                    </h5>
                                    <p class="card-text">
                                        <span class="badge badge-primary badge-custom">
                                            <i class="fas fa-flag"></i> {{ desb.nome_unidade || 'Sem unidade' }}
                                        </span>
                                        <span class="badge badge-info badge-custom ml-2">
                                            {{ desb.cargo }}
                                        </span>
                                    </p>
                                    <p class="card-text text-muted mb-0">
                                        <small><strong>Classe:</strong> {{ desb.classe_base || 'Não definida' }}</small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <button 
                                        @click="$emit('editar', desb)" 
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button 
                                        @click="$emit('excluir', desb)" 
                                        class="btn btn-sm btn-danger ml-2">
                                        <i class="fas fa-trash"></i> Excluir
                                    </button>
                                    <a 
                                        :href="'<?= site_url('pontuacao/historico/') ?>' + desb.id_desbravador" 
                                        class="btn btn-sm btn-info ml-2">
                                        <i class="fas fa-history"></i> Histórico
                                    </a>
                                </div>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </div>
        </div>
    `
};

// Componente de Formulário
const FormularioDesbravador = {
    props: ['desbravador', 'unidades', 'salvando'],
    data() {
        return {
            form: {
                id_desbravador: null,
                nome_completo: '',
                id_unidade: '',
                classe_base: '',
                cargo: 'Desbravador'
            }
        };
    },
    computed: {
        modoEdicao() {
            return !!this.form.id_desbravador;
        },
        tituloTela() {
            return this.modoEdicao ? 'Editar Desbravador' : 'Adicionar Desbravador';
        }
    },
    methods: {
        validarFormulario() {
            if (!this.form.nome_completo.trim()) {
                alert('O nome completo é obrigatório!');
                return false;
            }
            if (!this.form.cargo) {
                alert('O cargo é obrigatório!');
                return false;
            }
            return true;
        },
        salvarFormulario() {
            if (this.validarFormulario()) {
                this.$emit('salvar', { ...this.form });
            }
        },
        cancelar() {
            if (confirm('Deseja realmente cancelar? As alterações serão perdidas.')) {
                this.$emit('voltar');
            }
        }
    },
    mounted() {
        if (this.desbravador) {
            this.form = { ...this.desbravador };
        }
    },
    template: `
        <div class="container mt-4">
            <!-- Botão Voltar -->
            <button @click="cancelar" class="btn btn-secondary btn-voltar">
                <i class="fas fa-arrow-left"></i> Voltar
            </button>

            <!-- Cabeçalho -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1>
                        <i :class="modoEdicao ? 'fas fa-edit' : 'fas fa-plus-circle'"></i>
                        {{ tituloTela }}
                    </h1>
                </div>
            </div>

            <!-- Formulário -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-body p-4">
                            <form @submit.prevent="salvarFormulario">
                                <div class="form-group">
                                    <label for="nome_completo">
                                        <i class="fas fa-user"></i> Nome Completo *
                                    </label>
                                    <input 
                                        id="nome_completo"
                                        v-model="form.nome_completo" 
                                        type="text" 
                                        class="form-control form-control-lg" 
                                        placeholder="Digite o nome completo"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="id_unidade">
                                        <i class="fas fa-flag"></i> Unidade
                                    </label>
                                    <select 
                                        id="id_unidade"
                                        v-model="form.id_unidade" 
                                        class="form-control form-control-lg">
                                        <option value="">Selecione uma unidade</option>
                                        <option v-for="u in unidades" :key="u.id" :value="u.id">
                                            {{ u.nome }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="classe_base">
                                        <i class="fas fa-award"></i> Classe Base
                                    </label>
                                    <input 
                                        id="classe_base"
                                        v-model="form.classe_base" 
                                        type="text" 
                                        class="form-control form-control-lg"
                                        placeholder="Ex: Explorador, Orientador, etc.">
                                </div>

                                <div class="form-group">
                                    <label for="cargo">
                                        <i class="fas fa-briefcase"></i> Cargo *
                                    </label>
                                    <select 
                                        id="cargo"
                                        v-model="form.cargo" 
                                        class="form-control form-control-lg" 
                                        required>
                                        <option value="Desbravador">Desbravador</option>
                                        <option value="Secretário">Secretário</option>
                                        <option value="Capitão">Capitão</option>
                                    </select>
                                </div>

                                <hr class="my-4">

                                <div class="row">
                                    <div class="col-6">
                                        <button 
                                            type="button" 
                                            @click="cancelar" 
                                            class="btn btn-secondary btn-lg btn-block">
                                            <i class="fas fa-times"></i> Cancelar
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button 
                                            type="submit" 
                                            class="btn btn-success btn-lg btn-block" 
                                            :disabled="salvando">
                                            <span v-if="salvando" class="loading-spinner mr-2"></span>
                                            <i v-else class="fas fa-save"></i>
                                            {{ salvando ? 'Salvando...' : 'Salvar' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Informações adicionais -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Dica:</strong> Os campos marcados com * são obrigatórios.
                    </div>
                </div>
            </div>
        </div>
    `
};

// App Principal
createApp({
    components: {
        'listagem-desbravadores': ListagemDesbravadores,
        'formulario-desbravador': FormularioDesbravador
    },
    data() {
        return {
            telaAtual: 'listagem',
            desbravadores: [],
            unidades: [],
            carregando: true,
            salvando: false,
            desbravadorEdicao: null,
            alerta: {
                mensagem: '',
                tipo: 'success'
            }
        };
    },
    methods: {
        async carregarDados() {
            try {
                this.carregando = true;
                
                const respDesbravadores = await fetch('<?= site_url('desbravadores/listar_json') ?>');
                this.desbravadores = await respDesbravadores.json();
                
                const respUnidades = await fetch('<?= site_url('unidades/listar_json') ?>');
                this.unidades = await respUnidades.json();
                
            } catch (erro) {
                this.mostrarAlerta({ mensagem: 'Erro ao carregar dados: ' + erro.message, tipo: 'danger' });
            } finally {
                this.carregando = false;
            }
        },
        
        abrirFormulario(desbravador = null) {
            this.desbravadorEdicao = desbravador;
            this.telaAtual = 'formulario';
        },
        
        voltarListagem() {
            this.telaAtual = 'listagem';
            this.desbravadorEdicao = null;
        },
        
        async salvar(dados) {
            try {
                this.salvando = true;
                
                const url = dados.id_desbravador
                    ? '<?= site_url('desbravadores/atualizar_json') ?>'
                    : '<?= site_url('desbravadores/criar_json') ?>';
                
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dados)
                });
                
                const resultado = await resp.json();
                
                if (resultado.sucesso) {
                    this.mostrarAlerta({ mensagem: resultado.mensagem, tipo: 'success' });
                    this.voltarListagem();
                    await this.carregarDados();
                } else {
                    this.mostrarAlerta({ mensagem: resultado.mensagem || 'Erro ao salvar', tipo: 'danger' });
                }
                
            } catch (erro) {
                this.mostrarAlerta({ mensagem: 'Erro ao salvar: ' + erro.message, tipo: 'danger' });
            } finally {
                this.salvando = false;
            }
        },
        
        async confirmarExclusao(desbravador) {
            if (confirm(`Tem certeza que deseja excluir ${desbravador.nome_completo}?`)) {
                try {
                    const resp = await fetch('<?= site_url('desbravadores/deletar_json') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: desbravador.id_desbravador })
                    });
                    
                    const resultado = await resp.json();
                    
                    if (resultado.sucesso) {
                        this.mostrarAlerta({ mensagem: resultado.mensagem, tipo: 'success' });
                        await this.carregarDados();
                    } else {
                        this.mostrarAlerta({ mensagem: resultado.mensagem || 'Erro ao excluir', tipo: 'danger' });
                    }
                    
                } catch (erro) {
                    this.mostrarAlerta({ mensagem: 'Erro ao excluir: ' + erro.message, tipo: 'danger' });
                }
            }
        },
        
        mostrarAlerta(evento) {
            this.alerta = { mensagem: evento.mensagem, tipo: evento.tipo || 'success' };
            setTimeout(() => this.fecharAlerta(), 5000);
        },
        
        fecharAlerta() {
            this.alerta.mensagem = '';
        }
    },
    mounted() {
        this.carregarDados();
    }
}).mount('#app');
</script>

</body>
</html>