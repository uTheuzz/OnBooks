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
        ><v-row align="center" justify="center">Novo livro</v-row></v-card-title
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
              :rules="requiredField"
              label="Nome"
              ref="nome"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              :rules="requiredField"
              label="Categoria"
              ref="categoria"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              :rules="requiredField"
              label="Autor"
              ref="autor"
              outlined
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row align="center" justify="center">
          <v-col>
            <v-select
              :items="ebooks"
              ref="ebook"
              label="Ebook"
              outlined
              required
            ></v-select>
          </v-col>
          <v-col>
            <v-text-field
              label="Tamanho"
              ref="tamanho"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
              label="Peso"
              ref="peso"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-select
              :items="pessoas"
              :item-text="'nome'"
              :item-value="'id'"
              :rules="requiredField"
              label="Pessoa"
              ref="pessoa"
              @change="selectValue"
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
            Salvar <v-icon>mdi-content-save</v-icon>
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
    this.getPessoas();
    this.livro = this.activeEditItem;
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        let refs = this.$refs;

        api
          .post(
            "api/livros",
            {
              nome: refs.nome.internalValue,
              categoria: refs.categoria.internalValue,
              autor: refs.autor.internalValue,
              ebook: refs.ebook.internalValue,
              tamanho: refs.tamanho.internalValue,
              peso: refs.peso.internalValue,
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
            console.log(response.data);
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
    },
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
