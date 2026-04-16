export default {
    props:{listar:{
        type: Object,
        default: () => ({ data: []})    
    }},
    data(){
        return {
            name:"Progresso",
            itemPesquisar:"",
            tirateima:[]
        }
    },
    methods: {
        listarMethod(){
            if(!this.listar || !this.listar.data || !this.listar.data.length > 0) return [];
              const textoPesquisar =   this.itemPesquisar.toLowerCase();
              this.tirateima = this.listar.data.filter(item=>{
                return item.nome_completo.toLowerCase().includes(textoPesquisar);
              });
        }
              
    },
    watch:{
        listar:{
            handler(novoValor){
                if(novoValor && novoValor.data){
                    console.log('listar pronto',novoValor);
                 
                }
            },
            immediate: true
        }
    },
    computed:{
        itemsFiltrados(){
            if(!this.listar.data || !this.listar.data.length) return[];
              const textoPesquisar =   this.itemPesquisar.toLowerCase();
              console.log('arr',this.listar);
              return this.listar.data.filter(item=>{
                return item.nome_desbravador.toLowerCase().includes(textoPesquisar);
              });
        }
    },
    template:`
        <div class="container mt-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1><i class="fas fa-users"></i> {{nome}}</h1>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" @click="$emit('editar')">
                        <i class="fas fa-plus"></i> Adicionar x
                    </button>
                </div>
            </div>
            <div class="card mb-12">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                             <input 
                                type="text"
                                v-model="itemPesquisar" 
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
           
            <div class="row mt-2">
                 <div class="col-md-6 col-lg-4 mb-3" 
                 v-for="cantinhoItem in itemsFiltrados" 
                 :key="cantinhoItem.id" >
                    <div class="card card-hover h-100">
                        <div class="card-body">
                             <h5 class="card-title">
                                <i class="fas fa-user-circle text-primary"></i> 
                                         {{cantinhoItem.nome_desbravador}}
                            </h5>
                            
                            <div class="card-text">
                                <div class="row">
                                        <span class="col-sm m-2 p-2 badge badge-primary badge-custom">
                                            <i class="fas fa-flag"></i> Unidade <b> - </b>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <button @click="$emit('editar',cantinhoItem)"
                                    class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button
                                        @click="$emit('excluir',cantinhoItem)" 
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