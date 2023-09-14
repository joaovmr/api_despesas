<template>
  <q-page
    class="bg-light-green window-height window-width row justify-center items-center"
  >
    <div class="column">
      <div class="row">
        <h5 class="text-h5 text-white q-my-md">Gerencia de Despesas</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg shadow-1">
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input
                square
                filled
                clearable
                v-model="email"
                type="email"
                label="Email"
              />
              <q-input
                square
                filled
                clearable
                v-model="password"
                type="password"
                label="Senha"
              />
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn
              unelevated
              color="light-green-7"
              size="lg"
              class="full-width"
              label="Login"
              @click="login"
            />
          </q-card-actions>
          <q-card-section class="text-center q-pa-none">
            <p class="text-grey-6 cursor-pointer" @click = 'cadastro'>Ainda não possui conta? Clique aqui</p>
          </q-card-section>
          <q-card-section class="text-center q-pa-none">
            <p class="text-red" v-if="errorMessage">
              {{ errorMessage }}
            </p>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import axios from "axios";
import { api } from 'boot/axios'

export default {
  name: "LoginPage",

  data() {
    return {
      email: "",
      password: "",
      errorMessage: "",
    };
  },
  methods: {
    async login() {
      axios.defaults.withCredentials = true;
      try {
        await axios.get("http://127.0.0.1:8000/sanctum/csrf-cookie");

        const response = await api.post(
          "/login",
          {
            email: this.email,
            password: this.password,
          },
          {
            xsrfHeaderName: "X-XSRF-TOKEN",
            withCredentials: true,
          }
        );

        const token = response.data.access_token;
        if (token) {
          localStorage.setItem("authToken", token);
          api.defaults.headers["Authorization"] = `Bearer ${token}`;
          this.$router.push({ path: "despesas" });
        }
      } catch (error) {
        if (error.response) {
          this.errorMessage = error.response.data.message;
        }
      }
    },
    cadastro(){
        this.$router.push({ path: "cadastro" });
    },
  },
};
</script>

<style>
.q-card {
  width: 360px;
}
</style>
