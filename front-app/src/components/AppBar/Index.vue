<template>
  <v-app-bar app dark dense height="75" color="orange accent-4">
    <v-app-bar-nav-icon @click.stop="handleChangeDrawer()"></v-app-bar-nav-icon>

    <v-toolbar-title v-show="!isMobile">OnBooks</v-toolbar-title>

    <v-spacer></v-spacer>

    <v-text-field
      class="search"
      hide-details
      ref="text"
      append-icon="mdi-magnify"
      @click:append="searchBy()"
    ></v-text-field>

    <v-speed-dial
      class="user"
      v-model="fab"
      :top="top"
      :right="right"
      :direction="direction"
      :open-on-hover="hover"
      :transition="transition"
    >
      <template v-slot:activator>
        <v-btn v-model="fab" color="orange accent-4" fab>
          <v-icon v-if="fab"> mdi-close </v-icon>
          <v-icon v-else> mdi-account-circle </v-icon>
        </v-btn>
      </template>

      <v-tooltip left>
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="green" v-bind="attrs" v-on="on" to="perfil">
            <v-icon>mdi-account-details</v-icon>
          </v-btn>
        </template>
        <span>Perfil</span>
      </v-tooltip>

      <v-tooltip left>
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="red" v-bind="attrs" v-on="on" dark @click="logout()">
            <v-icon>mdi-logout</v-icon>
          </v-btn>
        </template>
        <span>Logout</span>
      </v-tooltip>
    </v-speed-dial>

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
  </v-app-bar>
</template>

<script>
import { mapGetters } from "vuex";
import api from "@/services/api.js";

export default {
  data: () => ({
    alert: { status: false, text: "", type: "success" },
    direction: "bottom",
    top: false,
    fab: false,
    fling: false,
    hover: true,
    tabs: null,
    right: false,
    transition: "slide-y-transition",
  }),
  computed: {
    ...mapGetters({
      drawer: "getSystemBarAndNavigation",
      isMobile: "getSystemBreakepointApp",
      searchedBooks: "getSystemSearchedBooks",
    }),
  },
  methods: {
    handleChangeDrawer() {
      this.$store.dispatch("systemBarAndNavigation", !this.drawer);
    },
    showAlert(status, text, type) {
      this.alert.status = status;
      this.alert.text = text;
      this.alert.type = type;
    },
    searchBy() {
      if (
        this.$refs.text.$refs.input.value === undefined ||
        this.$refs.text.$refs.input.value === ""
      ) {
        this.showAlert(true, "Informe algo para efetuar a busca...", "warning");
      } else {
        api
          .get(`api/searchby?text=${this.$refs.text.$refs.input.value}`)
          .then((response) => {
            console.log(response.data);

            if (response.data.status === "success") {
              this.showAlert(true, response.data.resume, response.data.status);
              this.$store.dispatch(
                "systemSearchedBooks",
                response.data.dados.original
              );
              this.$router.push("?temp");
              this.$router.push("/livros-buscados");
            } else {
              this.showAlert(true, response.data.resume, response.data.status);
            }
          });
      }
    },
    logout() {
      api
        .post("/logout", {
          headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "GET,POST,PUT,DELETE,OPTIONS",
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
          },
        })
        .then((response) => {
          console.log(response.data);
          if (response.data.status === "error") {
            this.alert.status = true;
            this.alert.text = response.data.resume;
            this.alert.type = response.data.status;
          } else {
            localStorage.removeItem("loggedUser");
            this.$store.dispatch("systemLoggedStatus", !this.isLoggedStatus);
            this.$router.push("/login");
          }
        });
    },
    
  },
};
</script>

<style scoped>
.search {
  padding-right: 30px;
  max-width: 300px;
}
.alert {
  position: absolute;
  top: 850px;
  z-index: -999;
}
#create .v-speed-dial {
  position: absolute;
}

#create .v-btn--floating {
  position: relative;
}
.user {
  padding-right: 10px;
}
</style>
