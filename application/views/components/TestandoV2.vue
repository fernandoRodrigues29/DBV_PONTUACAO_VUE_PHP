<template>
  <div class="container mt-4">
    <div class="row mb-4">
      <div class="col-md-6">
        <h1><i class="fas fa-users"></i> Video de outro lugar</h1>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ListagemTeste',
  props: {
    desbravadores: {
      type: Array,
      required: false
    },
    unidades: {
      type: Array,
      required: false
    },
    carregando: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      contador: 0
    };
  }
};
</script>