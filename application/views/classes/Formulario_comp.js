export default {
props: ['unidade', 'salvando','lista_desbravadores','lista_unidades'],
    data() {
        return {
            form: {
                id_cantinho: null,
                desbravador: '',
                presenca: false,
                hino: false,
                uniforme: false,
                atividades: false,
            },
            obj:null
        };
    },
    computed: {
        modoEdicao() {
            return !!this.form.id_cantinho;
        },
        tituloTela() {
            return this.modoEdicao ? 'Editar Unidade' : 'Adicionar Unidade';
        }
    },
    methods: {
        validarFormulario() {
            // if (!this.form.desbravador.trim()) {
            //     alert('O nome da unidade é obrigatório!');
            //     return false;
            // }
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
        },
        toggle(campo) {
            console.log('campo:',campo);
            this.form[campo] = !this.form[campo]
            console.log(this.ligado ? 'Ligado' : 'Desligado')
        },
        montarFormulario(obj){
           
            this.form.id_cantinho = obj.id;
            this.form.id_unidade = obj.id_unidade;
            this.form.desbravador = obj.id_desbravador;
            this.form.presenca = obj.presenca;
            this.form.hino = obj.hino;
            this.form.uniforme = obj.uniforme;
            this.form.atividades = obj.atividades;
        }
    },
    mounted() {
        if (this.unidade) {
            this.obj = { ...this.unidade };
            this.montarFormulario(this.obj);
        }
    },
    template: `
 <div class="container mt-4" v-if="form">
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
                                            <i class="fas fa-user"></i> Desbravador *
                                        </label>
                                            <select 
                                                class="form-control form-control-lg"
                                                v-model="form.desbravador" 
                                                requied>
                                                    <option v-for="item in lista_desbravadores" :key="item.id_desbravador" :value="item.id_desbravador"> 
                                                        {{item.nome_completo}} 
                                                    </option>
                                            </select>
                                    </div>
                                    
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Unidade*
                                        </label>
                                            <select 
                                                class="form-control form-control-lg"
                                                v-model="form.id_unidade" 
                                                requied>
                                                    <option v-for="item in lista_unidades.data" :key="item.id_unidade" :value="item.id_unidade"> 
                                                        {{item.nome_unidade}} 
                                                    </option>
                                            </select>
                                            
                                    </div> 
                                    
                                    </div>
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> presenca * {{form.presenca}}
                                        </label>
                                        <div class="switch" :class="{active: form.presenca}" @click="toggle('presenca')">
                                            <div class="circle"></div>
                                        </div>                                   
                                    </div>
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Hino * {{form.hino}}
                                        </label>
                                        <div class="switch" :class="{active: form.hino}" @click="toggle('hino')">
                                            <div class="circle"></div>
                                        </div>                                  
                                    </div>
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Cantinho *{{form.uniforme}}
                                        </label>
                                        <div class="switch" :class="{active: form.uniforme}" @click="toggle('uniforme')">
                                            <div class="circle"></div>
                                        </div>                                  
                                    </div>
                                    <div class="my-1">
                                        <label for="nome_completo">
                                            <i class="fas fa-user"></i> Atividades *{{form.atividades}}
                                        </label>
                                        <div class="switch" :class="{active: form.atividades}" @click="toggle('atividades')">
                                            <div class="circle"></div>
                                        </div>                                  
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
                                            :disabled="salvando"
                                            @click="salvarFormulario">
                                            <span v-if="salvando" class="loading-spinner mr-2"></span>
                                            <i v-else class="fas fa-save"></i>
                                            {{ salvando ? 'Salvando...' : 'Salvar' }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                               <!-- Informações adicionais -->
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Dica:</strong> Os campos marcados com * são obrigatórios.
                    </div>
                        </div>
                    </div>

                 
                </div>
            </div>
        </div>
    `
};