export default {
props: ['unidade', 'salvando','lista_desbravadores','lista_itens_classe'],
    data() {
        return {
            form: {
                desbravador: 0,
                classe_id: 0,
                // itens_marcados:[]
            },
            obj:null,
                // openSections: [false, false, false,false, false, false, false, false], // controla quais seções estão abertas
                openSections: [], // controla quais seções estão abertas
                    sections: [
                        {
                            title: "",
                            sigla: "",
                            items: [
                                { text: "", checked: false },
                            ]
                        }
                    ],
        };
    },
    computed: {
        modoEdicao() {
            return !!this.form.id_cantinho;
        },
        tituloTela() {
            return this.modoEdicao ? 'Editar Unidade' : 'Adicionar Unidade';
        },
                // Calcula o total de itens
                totalItems() {
                    return this.sections.reduce((total, section) => total + section.items.length, 0);
                },
                // Calcula quantos estão marcados
                checkedCount() {
                    return this.sections.reduce((total, section) => {
                        return total + section.items.filter(item => item.checked).length;
                    }, 0);
                },
                // Percentual de progresso
                progress() {
                    return this.totalItems > 0 
                        ? Math.round((this.checkedCount / this.totalItems) * 100) 
                        : 0;
                }
    },
    methods: {
        teste(){
            console.log('lista itens classe',this.lista_itens_classe)
        },
        ajustarDadosSecao(){
             const mapaGrupos = new Map([
                        ['DE', 'Desenvolvimento_espiritual'],
                        ['SO', 'Servindo_a_outros'],
                        ['SAF', 'Saude_aptidao_fisica'],
                        ['EN', 'Estudo_da_natureza'],
                        ['AA', 'Arte_de_acampar'],
                        ['DA', 'Desenvolvendo_a_amizade'],
                        ['OL', 'Organizacao_e_lideranca'],
                        ['EV', 'Estilo_de_vida']
                    ]);
              const arr_agrupados = this.lista_itens_classe.data.reduce((acc,item) =>{
                     
                    const nomeGrupo = mapaGrupos.get(item.grupo) ?? item.grupo;
                    const sigla = item.grupo;

                       
                        if(!acc[nomeGrupo]){
                            acc[nomeGrupo] = {
                                sigla: sigla,
                                items: []
                            };
                        }

                            acc[nomeGrupo].items.push(item);
                                return acc;
              }, {});
              
              const formatado = this.formatarArraySecction(arr_agrupados);
                this.sections = [];
                    this.sections = formatado;
        },
        formatarArraySecction(data){
            const arr = Object.keys(data).map(key => {
                return {
                    title: key,
                    sigla:data[key].sigla,
                    items: data[key].items.map(item=>({
                        id:item.id,
                        text: item.item,
                        checked: false
                    }))
                };
            });
            return arr; 
        },
        toggleSection(index) {
                    this.openSections[index] = !this.openSections[index];
                },
        validarFormulario() {
            // if (!this.form.desbravador.trim()) {
            //     alert('O nome da unidade é obrigatório!');
            //     return false;
            // }
            return true;
        },
        salvarFormulario() {
            // if (this.validarFormulario()) {
            //     this.$emit('salvar', { ...this.form });
            // }
            // if(this.validarFormulario()) return;

            const itensMarcados = [];

            this.sections.forEach(section => {
                section.items.forEach(item => {
                    if(item.checked && item.id){
                        itensMarcados.push(item.id);
                    }
                });
            });

            const payload = {
                ...this.form,
                itens_marcados: itensMarcados
            };

            console.log('payload para salvar',payload);
            this.$emit('salvar', payload);
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
           
            // this.form.id_cantinho = obj.id;
          
        }
    },
    mounted() {
       this.ajustarDadosSecao()
    },
    watch: {
        // Opcional: logar progresso sempre que mudar
        checkedCount(newVal) {
            console.log(`Progresso atual: ${newVal}/${this.totalItems} (${this.progress}%)`);}
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
                     
                    <!-- Cabeçalho -->
                    <div class="text-center mb-5">
                        <h1 class="header-title text-primary">✅ Checklist Progresso</h1>
                        <p class="lead text-muted">Feito com Vue 3 + Bootstrap 5</p>
                    </div>

                    <!-- Barra de Progresso -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Progresso Geral</h5>
                                <span class="fw-semibold">{{ checkedCount }} de {{ totalItems }} itens completados</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" 
                                     :class="{ 'bg-success': progress < 100, 'bg-primary': progress === 100 }"
                                     role="progressbar"
                                     :style="{ width: progress + '%' }">
                                    {{ progress }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Desbravador</h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-group">
                                    <label for="nome_completo">
                                        <i class="fas fa-user"></i> Desbravador *
                                    </label>
                                    <select 
                                        class="form-control form-control-lg"
                                        v-model="form.desbravador" 
                                        requied>
                                            <option value="1"> 
                                              Desbravador teste 1  
                                            </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">classe</h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="form-group">
                                    <label for="nome_completo">
                                        <i class="fas fa-user"></i> classe *
                                    </label>
                                    <select 
                                        class="form-control form-control-lg"
                                        v-model="form.classe_id" 
                                        requied>
                                            <option value="1"> 
                                              Classe teste 1  
                                            </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accordion -->
                    <div class="accordion accordion-flush shadow-sm" id="accordionChecklist">

                        <!-- Seção 1 -->
                        <div class="accordion-item" v-for="(valor,i) in sections" :key="i">
                            <h2 class="accordion-header">
                                <button class="accordion-button" :class="{ collapsed: !openSections[i] }" 
                                        type="button" @click="toggleSection(i)">
                                    📋 {{valor.title}}
                                </button>
                            </h2>
                            <div class="accordion-collapse" :class="{ collapse: true, show: openSections[i] }">
                                <div class="accordion-body p-0">
                                    <div v-for="(item, index) in valor.items" :key="index" class="check-item p-3 border-bottom">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   :id="'s'+i+'_'+index" 
                                                   v-model="item.checked">
                                            <label class="form-check-label" :for="'s'+i+'_'+index">
                                                {{ item.text }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
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
                                            :disabled="salvando">
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