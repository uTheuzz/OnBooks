<template>
  <v-container fluid class="my-container">
    <v-card min-width="100vh" max-height="700px" elevation="5">
      <div class="center" v-show="isLoading">
        <v-progress-circular
          indeterminate
          color="warning"
          :size="100"
        ></v-progress-circular>
      </div>

      <v-card-title v-show="!isLoading" class="card-title"
        ><v-row align="center" justify="center"
          >Lista de livros buscados</v-row
        ></v-card-title
      >
      <v-divider></v-divider>

      <v-simple-table
        v-show="!isLoading"
        class="my-table"
        fixed-header
        height="550px"
      >
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">#</th>
              <th class="text-left">Nome</th>
              <th class="text-left">Categoria</th>
              <th class="text-left">Codigo</th>
              <th class="text-left">Autor</th>
              <th class="text-left">ebook</th>
              <th class="text-left">Tamanho</th>
              <th class="text-left">Peso</th>
              <th class="text-left">Pessoa</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="livro in livros" :key="livro.id">
              <td>{{ livro.id_livro }}</td>
              <td>{{ livro.nome_livro }}</td>
              <td>{{ livro.categoria }}</td>
              <td>{{ livro.codigo }}</td>
              <td>{{ livro.autor }}</td>
              <td>{{ livro.ebook }}</td>
              <td>
                {{
                  livro.tamanho_do_arquivo === null
                    ? "-"
                    : livro.tamanho_do_arquivo
                }}
              </td>
              <td>{{ livro.peso === null ? "-" : livro.peso }}</td>
              <td>{{ livro.nome_pessoa }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card>
  </v-container>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data: () => ({
    livros: [],
    isLoading: true,
    alert: { status: false, text: "" },
  }),
  computed: {
    ...mapGetters({
      activeEditItem: "getSystemActiveEditItem",
      searchedBooks: "getSystemSearchedBooks",
    }),
  },
  mounted() {
    this.getLivrosBuscados();
  },
  methods: {
    getLivrosBuscados() {
      this.livros = this.searchedBooks;
      this.isLoading = false;
    },
  },
  watch: {
    searchedBooks: function (newValue) {
      this.livros = newValue;
    },
  },
};
</script>
<style scoped>
.my-container {
  padding-top: 50px;
}
.my-table {
  padding: 25px 15px 15px 15px;
}
.card-title {
  font-size: 35px;
  padding: 35px 0 35px 0;
}
.center {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding-top: 125px;
}
.v-progress-circular {
  margin: 1rem;
}
.alert {
  padding-top: 25px;
  display: block;
}
th {
  background-color: #ffbc6a !important;
  color: #000000 !important;
}
</style>
