import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    component: () => import("@/views/Login/Index.vue"),
  },
  {
    path: "/login",
    component: () => import("@/views/Login/Index.vue"),
  },
  {
    path: "/dashboard",
    component: () => import("@/views/Dashboard/Index.vue"),
  },
  {
    path: "/edicao",
    name: "edicao",
    component: () => import("@/views/Edicao/Index.vue"),
  },
  {
    path: "/novo-livro",
    component: () => import("@/views/NovoLivro/Index.vue"),
  },
  {
    path: "/livros-buscados",
    component: () => import("@/views/LivrosBuscados/Index.vue"),
  },
  {
    path: "/usuarios",
    component: () => import("@/views/Usuarios/Index.vue"),
  },
  {
    path: "/novo-usuario",
    component: () => import("@/views/NovoUsuario/Index.vue"),
  },
  {
    path: "/perfil",
    component: () => import("@/views/Perfil/Index.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  base: "/",
  routes,
});

export default router;
