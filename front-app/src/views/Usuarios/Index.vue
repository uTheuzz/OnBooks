<template>
  <v-container fluid class="my-container">
    <v-card min-width="100vh" min-height="700px" elevation="5">
      <div class="center" v-show="isLoading">
        <v-progress-circular
          indeterminate
          color="warning"
          :size="100"
        ></v-progress-circular>
      </div>

      <v-card-title v-show="!isLoading" class="card-title"
        ><v-row align="center" justify="center"
          >Lista de usuários</v-row
        ></v-card-title
      >

      <v-divider v-show="!isLoading"></v-divider>

      <v-simple-table
        v-show="!isLoading"
        v-if="users.length >= 1"
        class="my-table"
        fixed-header
        height="500px"
      >
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">#</th>
              <th class="text-left">Nome</th>
              <th class="text-left">Email</th>
              <th class="text-left">Criação</th>
              <th class="text-left">Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.nome }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.created_at | formatDate }}</td>
              <td>
                <!-- <v-icon small class="mr-2" @click="editItem(user)">
                  mdi-pencil
                </v-icon> -->
                <v-icon small @click="deleteItem(user)"> mdi-delete </v-icon>
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
      <v-row v-else class="empty" align="center" justify="center">
        <v-btn v-show="!isLoading" color="primary" @click="newUser">
          Novo usuário<v-icon>mdi-plus-box</v-icon>
        </v-btn>
      </v-row>
      <v-row v-show="users.length >= 1" class="new-user-btn">
        <v-btn class="user-btn" v-show="!isLoading" color="primary" @click="newUser">
          Novo usuário<v-icon size="15">mdi-plus-box</v-icon>
        </v-btn>
      </v-row>
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
  </v-container>
</template>

<script>
import api from "@/services/api.js";
import { mapGetters } from "vuex";
import moment from "moment";
import 'moment/locale/pt-br';

export default {
  data: () => ({
    users: [],
    isLoading: true,
    alert: { status: false, text: "", type: "success" },
  }),
  computed: {
    ...mapGetters({
      activeEditItem: "getSystemActiveEditItem",
      loggedUser: "getSystemLoggedUser"
    }),
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      api
        .get("api/users")
        .then((response) => {
          this.users = response.data;
        })
        .finally(() => (this.isLoading = false));
    },
    editItem(item) {
      this.$store.dispatch("systemActiveEditItem", item);
      this.$router.push("/edicao");
    },
    deleteItem(item) {
      if(this.loggedUser.user.id === item.id) {
        this.alert.status = true;
        this.alert.type = 'error';
        this.alert.text = 'Você não pode se deletar...';
      }else{
        this.isLoading = true;
        api.delete(`api/users/${item.id}`).then((response) => {
          this.alert.status = true;
          this.alert.type = response.data.status;
          this.alert.text = response.data.resume;
          this.getUsers();
        }).finally(() => (this.isLoading = false));
      }
    },
    newUser() {
      this.$router.push("/novo-usuario");
    },
  },
  filters: {
     formatDate(value){
         if (value) {
           return moment(String(value)).format('DD/MM/YYYY')
          }
      }
  }
};
</script>
<style scoped>
.my-container {
  padding-top: 50px;
}
.my-table {
  padding: 25px 15px 5px 15px;
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
  padding-top: 250px;
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
.empty {
  padding-top: 270px;
}
.new-user-btn{
  display: flex;
  justify-content: right;
  padding-right: 25px;
  padding-top: 50px;
}
.user-btn{
  font-size: 10px;
}
</style>
