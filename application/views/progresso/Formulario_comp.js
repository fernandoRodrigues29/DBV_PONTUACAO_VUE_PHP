import grupos from './grupos.js';
export default {
props: {
    objeto:{
    type:Object,
    default:()=>({})
}, 
salvando:{
    type:Boolean,
    default:false
},
lista_desbravadores:{
    type:Array,
    default: () => []
},
lista_itens_classe:{
    type:Object,
    default: () => ({data:[]})
},
lista_itens_classe_marcados:{
    type: Array,
    default: () => []
},
id_desbravador_marcado:{
    type: Number,
    default: 0
},
id_classe_marcado:{
    type: Number,
    default: 0
},
lista_dbv_json:{
    type:Object,
    default: () => ({ data: []})
},
lista_classe_json:{
    type:Object,
    default: () => ({ data: []})
}
},

    data() {
        return {
            form: {
                desbravador: 0,
                classe_id: 0,
            },
            obj:null,
            origem:'editar',
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
                itensPreMarcados:[]    
        };
    },
    computed: {
        modoEdicao() {
            return !!this.form.id_progresso;
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
        preCarregar(){
            
            if(this.id_desbravador_marcado !== undefined){
                this.form.desbravador = this.id_desbravador_marcado;
            }

            if(this.id_classe_marcado !== undefined){
                this.form.classe_id = this.id_classe_marcado;
            }

            if(this.objeto.id_progresso !== undefined){
                this.form.id_progresso = this.objeto.id_progresso;
            }
            if(Object.keys(this.objeto).length === 0 ){
                this.origem = 'cadastrar';
            }
            
        },
        ajustarDadosSecao(){
             this.itensPreMarcados = null;
             if(Array.isArray(this.lista_itens_classe_marcados?.data) && this.lista_itens_classe_marcados?.data.length >0){
                this.itensPreMarcados = this.lista_itens_classe_marcados.data.map(t => t.id_item);
             }   
            
           const mapaGrupos = grupos;
      
                    if(!this.lista_itens_classe?.data){
                        console.error('listra lista_itens_classe inválida');
                        return;
                    }
              
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
              const formatado = this.formatarArraySecction(arr_agrupados,this.itensPreMarcados);
               
              this.sections = [];
                    this.sections = formatado;
        },
        formatarArraySecction(data,idsParaMarcar=[]){
            let arr = {};
            if(idsParaMarcar !== null ){
                arr = Object.keys(data).map(key => {
                    return {
                        title: key,
                        sigla:data[key].sigla,
                        items: data[key].items.map(item=>({
                            id:item.id,
                            text: item.item,
                            checked: idsParaMarcar.includes(item.id)
                        }))
                    };
                });

            }else{
                    arr = Object.keys(data).map(key => {
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
            }
            return arr; 
        },
        toggleSection(index) {
           this.openSections[index] = !this.openSections[index];
        },
        salvarFormulario() {
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
                itens_marcados: itensMarcados,
                origem:this.origem
            };
            
            this.$emit('salvar', payload);
        },
        abrirModalCancelar(){
            this._modalCancelar?.show();
        },
        cancelar() {
             this._modalCancelar?.hide();
            this.$emit('voltar');
        },
        toggle(campo) {
            this.form[campo] = !this.form[campo]
        }
    },
    watch:{
        lista_itens_classe_marcados(){
            this.ajustarDadosSecao();
            this.openSections = new Array(this.sections.length).fill(false);
        }
    },
    mounted() {
      this.preCarregar();
      this.ajustarDadosSecao();
      this.openSections = new Array(this.sections.length).fill(false);
       
        this.$nextTick(()=>{
            const el = document.getElementById('modalCancelar');
                if(el){
                    this._modalCancelar = new bootstrap.Modal(el);
                }
        });
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
                   
                    <!-- Modal -->
                    <div class="modal fade" id="modalCancelar" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar cancelamento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Deseja realmente cancelar? As alterações serão perdidas.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                                    <button type="button" class="btn btn-danger" @click="cancelar">Sim, cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        required>
                                            <option value="" selected >Escolha o Desbravador</option>
                                            <option  v-for="(dbv,index) in lista_dbv_json.data" :key="index" :value="dbv.id_desbravador" > 
                                                {{dbv.nome_completo}}
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
                                        required>
                                            <option value="" selected >Escolha a Classe</option>
                                            <option v-for="(clss,index) in lista_classe_json.data" :value="clss.id" :key="clss.id"> 
                                              {{clss.nome}}  
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
                                            class="btn btn-secondary btn-lg btn-block"
                                            @click="abrirModalCancelar"
                                        >
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