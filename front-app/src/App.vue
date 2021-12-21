<template>
  <v-app>
    <Navigation />

    <AppBar v-show="isLoggedStatus" />

    <v-main>
      <router-view></router-view>
    </v-main>

    <v-footer>
      <!-- -->
    </v-footer>
  </v-app>
</template>

<script>
import AppBar from "./components/AppBar/Index.vue";
import Navigation from "./components/Navigation/Index.vue";
// import LoaderDialog from './components/LoaderDialog/Index.vue'
import { mapGetters } from "vuex";

export default {
  name: "App",
  components: {
    AppBar,
    Navigation,
  },
  computed: {
    ...mapGetters({
      breakpointApp: "getSystemBreakepointApp",
      isLoggedStatus: "getSystemLoggedStatus",
    }),
    breakpoint: function () {
      return this.$vuetify.breakpoint.name;
    },
  },
  methods: {
    isMobile() {
      return (
        this.breakpoint === "md" ||
        this.breakpoint === "sm" ||
        this.breakpoint === "xs"
      );
    },
    setIsMobile() {
      this.$store.dispatch("systemBreakepointApp", this.isMobile());
    },
    isLogged() {
      if (!this.isLoggedStatus) {
        this.$router.push("/login");
      }
    },
  },
  mounted() {
    this.isLogged();
    this.setIsMobile();
  },
};
</script>
<style lang="scss" scoped>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  background-color: #f0f0f0;
  background-image: url("./assets/bg.svg");
  background-repeat: repeat;
  background-position: center;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
