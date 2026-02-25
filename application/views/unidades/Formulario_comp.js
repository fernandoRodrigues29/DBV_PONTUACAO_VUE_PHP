export default {
props: ['unidade', 'salvando'],
    data() {
        return {
            form: {
                id_unidade: null,
                nome_unidade: '',
                classe_base:''
            }
        };
    },
    computed: {
        modoEdicao() {
            return !!this.form.id_unidade;
        },
        tituloTela() {
            return this.modoEdicao ? 'Editar Unidade' : 'Adicionar Unidade';
        }
    },
    methods: {
        validarFormulario() {
            if (!this.form.nome_unidade.trim()) {
                alert('O nome da unidade é obrigatório!');
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
        if (this.unidade) {
            this.form = { ...this.unidade };
        }else{
            this.form = { id_unidade:0,nome_unidade:"padrão teste"};
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
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Unidade *
                                        </label>
                                        <input 
                                        id="nome_unidade"
                                        v-model="form.nome_unidade" 
                                        type="text" 
                                        class="form-control form-control-lg" 
                                        placeholder="Digite o nome da unidade"
                                        required>                                   
                                    </div>
                                     <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Classe Base *
                                        </label>
                                        <input 
                                            id="nome_unidade"
                                            v-model="form.classe_base" 
                                            type="text" 
                                            class="form-control form-control-lg" 
                                            placeholder="Digite a classe base"
                                            required>
                                     </div>
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