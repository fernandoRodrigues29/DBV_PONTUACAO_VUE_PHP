export default {
    props:['listar'],
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
                    <button class="btn btn-success" @click="$emit('editar')">
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
                 <div class="col-md-6 col-lg-4 mb-3" v-for="unidadeItem in listar.data" :key="unidadeItem.id_unidade" >
                    <div class="card card-hover h-100">
                        <div class="card-body">
                             <h5 class="card-title">
                                <i class="fas fa-user-circle text-primary"></i> 
                                         {{unidadeItem.nome_unidade}}
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
                                <small><strong>id:</strong> {{unidadeItem.id_unidade}}</small>
                            </p>
                        </div>
                        <div class="card-footer bg-white">
                            <button @click="$emit('editar',unidadeItem)"
                                    class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button
                                        @click="$emit('excluir',unidadeItem)" 
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