// Componente de Listagem
const listagemTeste = {
    props: ['desbravadores', 'unidades', 'carregando'],
    data() {
        return {
            contador:0
        };
    },
    template: `
        <div class="container mt-4">
            <!-- Cabeçalho -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1><i class="fas fa-users"></i> Video de outro lugar</h1>
                </div>
            </div>
        </div>
    `
};


export default listagemTeste;
