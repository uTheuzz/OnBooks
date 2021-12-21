<template>
  <v-row>
    <v-col class="login-side" align="center" justify="center">
      <v-card class="card-login" max-width="700px" elevation="5">
        <div class="center" v-if="isLoading">
          <v-progress-circular
            indeterminate
            color="warning"
            :size="100"
          ></v-progress-circular>
        </div>

        <div class="forms" v-else>
          <v-card-title v-show="!loaded" class="card-title"
            ><v-row align="center" justify="center">Login</v-row></v-card-title
          >

          <v-form v-show="!loaded" ref="form" v-model="valid" lazy-validation>
            <v-col align="center" justify="center">
              <v-text-field
                :rules="emailRules"
                ref="loginemail"
                label="E-mail"
                required
              ></v-text-field>

              <v-text-field
                :value="senha"
                :rules="senhaRules"
                ref="loginsenha"
                label="Senha"
                :append-icon="v ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append="() => (v = !v)"
                :type="v ? 'password' : 'text'"
                @input="(_) => (senha = _)"
                required
              ></v-text-field>

              <v-row class="btns" align="center" justify="center">
                <v-btn
                  :disabled="!valid"
                  color="primary"
                  class="mr-4"
                  @click="validate"
                >
                  Login <v-icon>mdi-login</v-icon>
                </v-btn>

                <v-btn color="warning" @click="registerForm">
                  Cadastro <v-icon>mdi-account-circle</v-icon>
                </v-btn>
              </v-row>
            </v-col>
          </v-form>

          <v-card-title v-show="loaded" class="card-title"
            ><v-row align="center" justify="center"
              >Cadastro</v-row
            ></v-card-title
          >

          <v-form
            v-show="loaded"
            ref="formregister"
            v-model="valid"
            lazy-validation
          >
            <v-col align="center" justify="center">
              <v-text-field
                v-model="nome"
                :rules="nomeRules"
                ref="regnome"
                label="Nome"
                required
              ></v-text-field>

              <v-text-field
                v-model="email"
                :rules="emailRules"
                ref="regemail"
                label="E-mail"
                required
              ></v-text-field>

              <v-text-field
                v-model="senha"
                :value="senha"
                :rules="senhaRules"
                ref="regsenha"
                label="Senha"
                :append-icon="v ? 'mdi-eye' : 'mdi-eye-off'"
                @click:append="() => (v = !v)"
                :type="v ? 'password' : 'text'"
                @input="(_) => (senha = _)"
                required
              ></v-text-field>

              <v-row class="btns" align="center" justify="center">
                <v-btn
                  :disabled="!valid"
                  color="primary"
                  class="mr-4"
                  @click="registerUser"
                >
                  Salvar <v-icon>mdi-clipboard-account</v-icon>
                </v-btn>

                <v-btn color="warning" @click="registerForm">
                  Voltar <v-icon>mdi-keyboard-return</v-icon>
                </v-btn>
              </v-row>
            </v-col>
          </v-form>
        </div>
      </v-card>
      <div class="alert">
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
    </v-col>
    <v-col class="img-login">
      <v-img
        src="../../assets/login_bg2.svg"
        max-height="1000"
        max-width="1000"
      ></v-img>
    </v-col>
  </v-row>
</template>

<script>
import api from "@/services/api.js";
import { mapGetters } from "vuex";

export default {
  data: () => ({
    valid: true,
    v: true,
    nome: "",
    email: "",
    senha: "",
    alert: { status: false, text: "", type: "success" },
    isLoading: false,
    loaded: false,
    emailRules: [
      (v) => !!v || "E-mail é necessário",
      (v) => /.+@.+\..+/.test(v) || "Informe um e-mail válido",
    ],
    senhaRules: [(v) => !!v || "Senha é necessária"],
    nomeRules: [(v) => !!v || "Informe o nome"],
  }),
  computed: {
    ...mapGetters({
      isLoggedStatus: "getSystemLoggedStatus",
    }),
  },
  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.loginUser();
      } else {
        console.log("Informações inválidas!");
      }
    },
    registerUser() {
      this.isLoading = true;
      if (this.$refs.formregister.validate()) {
        let refs = this.$refs;

        api
          .post(
            "/register",
            {
              name: refs.regnome.internalValue,
              email: refs.regemail.internalValue,
              password: refs.regsenha.internalValue,
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
              if (
                response.data.error_resume.validator.customMessages.unique
                  .length > 0
              ) {
                this.alert.status = true;
                this.alert.text =
                  response.data.error_resume.validator.customMessages.unique;
                this.alert.type = response.data.status;
              } else {
                this.alert.status = true;
                this.alert.text = response.data.resume;
                this.alert.type = response.data.status;
              }
            } else {
              this.$store.dispatch("systemLoggedUser", response.data);
              this.$store.dispatch("systemLoggedStatus", !this.isLoggedStatus);
              this.$router.push("/dashboard");
            }
          })
          .finally(() => (this.isLoading = false));
      } else {
        console.log("Informações inválidas!");
      }
    },
    async registerForm() {
      this.isLoading = !this.isLoading;
      await setTimeout(
        () => (
          (this.isLoading = !this.isLoading), (this.loaded = !this.loaded)
        ),
        1000
      );
    },
    loginUser() {
      this.isLoading = true;
      let refs = this.$refs;
      api
        .post(
          "/login",
          {
            email: refs.loginemail.internalValue,
            password: refs.loginsenha.internalValue,
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
            this.alert.status = true;
            this.alert.text = response.data.resume;
            this.alert.type = response.data.status;
          } else {
            this.$store.dispatch("systemLoggedUser", response.data);
            this.$store.dispatch("systemLoggedStatus", !this.isLoggedStatus);
            this.$router.push("/dashboard");
          }
        })
        .finally(() => (this.isLoading = false));
    },
  },
};
</script>
<style scoped>
.btns {
  padding: 100px 0px 20px 0px;
}
.login-side {
  padding: 100px 0px 20px 0px;
}
.card-title {
  font-size: 50px;
  padding: 50px 0 40px 0;
}
.alert {
  width: 700px;
  padding-top: 25px;
  display: block;
}
</style>
