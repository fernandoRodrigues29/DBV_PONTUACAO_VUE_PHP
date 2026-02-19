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
                    <span>teste${unidades}</span>
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

if(typeof module !== 'undefined' && module.exports){
    module.exports = ListagemDesbravadores;
}