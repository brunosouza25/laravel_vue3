import { createApp } from 'vue'
import { createPinia } from "pinia"
import App from './App.vue'
import "./assets/main.css";
import router from "./router"
import { setupCalendar } from 'v-calendar';


const app = createApp(App);
app.use(createPinia())
app.use(router)
app.mount('#app')
app.use(setupCalendar, {})
