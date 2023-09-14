<!-- 
<template>
  <div class="q-pa-md">
    <q-btn label="login" @click="login"></q-btn>
    <q-btn label="data" @click="getData"></q-btn>
  </div>
</template>

<script>
import axios from "axios";
import { defineComponent } from "vue";

export default defineComponent({
  name: "MyUsers",
  setup() {
    const form = {
      email: "joaomorais309@gmail.com",
      password: "123456abc",
    };
    const login = () => {
      axios.defaults.withCredentials = true;
      const response = axios
        .get("http://127.0.0.1:8000/sanctum/csrf-cookie")
        .then((response) => {
          console.log(response);
          return axios
            .post("http://127.0.0.1:8000/api/login", form, {
              xsrfHeaderName: "X-XSRF-TOKEN",
              withCredentials: true,
            })
            .then((response) => {
              console.log(response);
              const token = response.data.access_token; 
              if (token) {
                localStorage.setItem("authToken", token);
              }
              return response;
            });
        });
      return response;
    };

    const getData = () => {
      axios.defaults.withCredentials = true;

      // Get the token from where you've stored it (e.g., local storage)
      const authToken = localStorage.getItem("authToken"); // Replace with your storage method

      // Set the token in the Axios headers
      axios.defaults.headers.common["Authorization"] = `Bearer ${authToken}`;

      const response = axios
        .get("http://127.0.0.1:8000/api/despesas")
        .then((response) => {
          console.log(response);
        });
      return response;
    };

    return {
      login,
      getData,
    };
  },
});
</script> -->

<template>
  <div>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    login() {
      // Add your login logic here (e.g., Axios POST request to Laravel Sanctum)
    },
  },
};
</script>
