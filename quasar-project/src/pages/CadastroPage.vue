<template>
  <q-page
    class="bg-light-green window-height window-width row justify-center items-center"
  >
    <div class="column">
      <div class="row">
        <h5 class="text-h5 text-white q-my-md">Gestão de Despesas</h5>
      </div>
      <div class="row">
        <q-card square bordered class="q-pa-lg shadow-1">
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input square filled clearable v-model="name" label="Nome" />
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
              <q-input
                square
                filled
                clearable
                v-model="passwordConfirmation"
                type="password"
                label="Confirme a Senha"
              />
            </q-form>
          </q-card-section>
          <q-card-actions class="q-px-md">
            <q-btn
              unelevated
              color="light-green-7"
              size="lg"
              class="full-width"
              label="Cadastrar"
              @click="register"
            />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
<script>
import { api } from 'boot/axios'
export default {
  name: "CadastroPage",

  data() {
    return {
      name: "",
      email: "",
      password: "",
      passwordConfirmation: "",
    };
  },

  methods: {
    async register() {
      const registrationData = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.passwordConfirmation,
      };

      try {
        const response = await api.post(
          "/register",
          registrationData
        );

        this.$router.push({ path: '/' });
        
      } catch (error) {
        // Handle registration errors (e.g., display error messages)
        console.error(error);
      }
    },
  },
};
</script>
