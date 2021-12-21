<template>
  <v-navigation-drawer
    app
    temporary
    v-model="drawer"
    :clipped="$vuetify.breakpoint.lgAndUp"
  >
    <v-list dense>
      <template v-for="(item, index) in itemsMenu">
        <v-list-item
          link
          exact
          :to="item.path"
          :key="index"
          color="primary"
          v-if="!item.children"
          @click.stop="closeNavigationDrawer()"
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>

          <v-list-item-content>
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group
          v-else
          append-icon
          :key="index"
          v-model="item.model"
          :prepend-icon="item.model ? item.icon : item['icon-alt']"
        >
          <template v-slot:activator>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>{{ item.text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>

          <v-list-item
            exact
            :key="i"
            :to="child.path"
            v-for="(child, i) in item.children"
          >
            <v-list-item-action v-if="child.icon">
              <v-icon>{{ child.icon }}</v-icon>
            </v-list-item-action>

            <v-list-item-content>
              <v-list-item-title>{{ child.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { mapGetters } from "vuex";
import menus from "@/config/menus.js";

export default {
  name: "SystemNavigations",

  data: () => ({}),

  computed: {
    ...mapGetters({
      systemBarAndNavigation: "getSystemBarAndNavigation",
    }),

    itemsMenu: {
      get() {
        return menus.filter((item) => {
          return true;
        });
      },
    },

    drawer: {
      get() {
        return this.systemBarAndNavigation;
      },
      set(status) {
        this.$store.dispatch("systemBarAndNavigation", status);
      },
    },
  },

  methods: {
    closeNavigationDrawer() {
      this.itemsMenu.forEach((valor) => {
        if (valor["model"]) {
          valor["model"] = false;
        }
      });
    },
  },
};
</script>
