// Componente_alfa.js
export default {
  name: "controle",
  data() {
    return {
      conteudo: "teste123"
    };
  },
  template: `
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-6">
          <h1>Teste de componentes</h1>
          <span>{{ conteudo }}</span>
        </div>
      </div>
    </div>
  `
};