import { boot } from "quasar/wrappers";
import axios from "axios";

axios.defaults.withCredentials = true;

export const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  headers: {
    ...(localStorage.getItem("authToken")
      ? { Authorization: `Bearer ${localStorage.getItem("authToken")}` }
      : {}),
  },
});

export default boot(({ app }) => {

  app.config.globalProperties.$axios = axios;

  app.config.globalProperties.$api = api;
});

