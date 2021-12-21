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
        ><v-row align="center" justify="center">Novo usuário</v-row></v-card-title
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
              label="Email"
              ref="email"
              outlined
              required
            ></v-text-field>
          </v-col>
          <v-col>
            <v-text-field
                v-model="senha"
                :value="senha"
                :rules="requiredField"
                ref="senha"
                label="Senha"
                :append-icon="v ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append="() => (v = !v)"
                :type="v ? 'password' : 'text'"
                @input="(_) => (senha = _)"
                outlined
                required
            ></v-text-field>
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

          <v-btn color="warning" @click="returnPage">
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
    valid: true,
    v: true,
    senha: '',
    alert: { status: false, text: "", type: "success" },
    pessoas: [],
    isLoading: false,
    selectdOption: 0,
    requiredField: [(v) => !!v || "Preencha o campo"],
  }),
  methods: {
    validate() {
      this.isLoading = true;
      if (this.$refs.form.validate()) {
        let refs = this.$refs;

        api
          .post(
            "/register",
            {
              name: refs.nome.internalValue,
              email: refs.email.internalValue,
              password: refs.senha.internalValue,
            },
            {
              headers: {
                "Content-Type": "application/json",
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Methods": "GET,POST,PUT,DELETE,OPTIONS",
                "Access-Control-Allow-Headers": "Content-Type, Authorization",
              },
            }
          )
          .then((response) => {
            if (response.data.status === "error") {
              if (response.data.error_resume.validator.customMessages.unique.length > 0) {
                this.alert.status = true;
                this.alert.text = response.data.error_resume.validator.customMessages.unique;
                this.alert.type = response.data.status;
              } else {
                this.alert.status = true;
                this.alert.text = response.data.resume;
                this.alert.type = response.data.status;
              }
            } else {
              this.alert.status = true;
              this.alert.text = response.data.resume;
              this.alert.type = response.data.status;
            }
          })
          .finally(() => (this.isLoading = false));
      } else {
        console.log("Informações inválidas!");
      }
    },
    returnPage() {
      this.$router.push("/dashboard");
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
  padding: 50px 25px 0 25px;
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
