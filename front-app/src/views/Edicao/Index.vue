<template>
  <v-container fluid class="my-container">
    <v-card min-width="100vh" min-height="400px" elevation="5">
      <div class="center" v-show="isLoading">
        <v-progress-circular
          indeterminate
          color="warning"
          :size="100"
        ></v-progress-circular>
      </div>

      <v-card-title v-show="!isLoading" class="card-title"
        ><v-row align="center" justify="center">Edição</v-row></v-card-title
      >

      <v-form
        ref="form"
        v-model="valid"
        lazy-validation
        class="my-form"
        v-show="!isLoading"
      >
        <v-row align="center" justify="center">
          <v-col>
            <v-text-field
              v-model="livro.nome"
              ref="nome"
              label="Nome"
              :rules="requiredField"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              v-model="livro.categoria"
              ref="categoria"
              label="Categoria"
              :rules="requiredField"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              v-model="livro.autor"
              ref="autor"
              label="Autor"
              :rules="requiredField"
              outlined
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row align="center" justify="center">
          <v-col>
            <v-select
              :items="ebooks"
              v-model="livro.ebook"
              ref="ebook"
              label="Ebook"
              outlined
              required
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field
              v-model="livro.tamanho_do_arquivo"
              ref="tamanho"
              label="Tamanho"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              v-model="livro.peso"
              ref="peso"
              label="Tamanho"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-select
              :items="pessoas"
              :item-text="'nome'"
              :item-value="'id'"
              label="Pessoa"
              @change="selectValue"
              :rules="requiredField"
              outlined
              required
            ></v-select>
          </v-col>
        </v-row>

        <v-row class="btns" align="center" justify="center">
          <v-btn
            :disabled="!valid"
            color="primary"
            class="mr-4"
            @click.prevent="validate"
          >
            Editar <v-icon>mdi-content-save-edit</v-icon>
          </v-btn>

          <v-btn color="warning" @click="$router.back()">
            Voltar <v-icon>mdi-keyboard-return</v-icon>
          </v-btn>
        </v-row>
      </v-form>
    </v-card>
    <div class="alert" id="alert">
      <v-alert
        v-model="alert.status"
        v-bind:type="alert.type"
        border="bottom"
        transition="scale-transition"
        dismissible
      >
        {{ alert.text }}
      </v-alert>
    </div>
  </v-container>
</template>

<script>
import api from "@/services/api.js";
import { mapGetters } from "vuex";

export default {
  data: () => ({
    livro: [],
    valid: true,
    ebooks: [true, false],
    alert: { status: false, text: "", type: "success" },
    pessoas: [],
    isLoading: true,
    selectdOption: 0,
    requiredField: [(v) => !!v || "Preencha o campo"],
  }),
  computed: {
    ...mapGetters({
      activeEditItem: "getSystemActiveEditItem",
    }),
  },
  mounted() {
    this.getLivros();
    this.getPessoas();
    this.livro = this.activeEditItem;
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        let refs = this.$refs;

        api
          .put(
            `api/livros/${this.livro.id}`,
            {
              nome: refs.nome.value,
              categoria: refs.categoria.value,
              autor: refs.autor.value,
              ebook: refs.ebook.value,
              tamanho: refs.tamanho.value,
              peso: refs.peso.value,
              id_pessoa: this.selectdOption,
            },
            {
              headers: { "Content-Type": "application/json" },
            }
          )
          .then((response) => {
            this.alert.status = true;
            this.alert.type = response.data.status;
            this.alert.text = response.data.resume;
          });
      } else {
        console.log("Informações inválidas!");
      }
    },
    getLivros() {
      api.get("api/livros").then((response) => {
        this.livros = response.data;
      });
    },
    getPessoas() {
      api.get("api/pessoas").then((response) => {
        this.pessoas = response.data;
        this.isLoading = false;
      });
    },
    selectValue(e) {
      this.selectdOption = e;
    }
  },
};
</script>
<style scoped>
.my-container {
  padding-top: 50px;
}
.btns {
  padding: 100px 0px 20px 0px;
}
.card-title {
  font-size: 50px;
  padding: 50px 0 50px 0;
}
.my-form {
  padding: 0 25px 0 25px;
}
.center {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding-top: 100px;
}
.v-progress-circular {
  margin: 1rem;
}
.alert {
  padding-top: 100px;
  display: block;
}
</style>
