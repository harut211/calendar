import './bootstrap';
import { createApp } from 'vue';
import '@mdi/font/css/materialdesignicons.css';
import App from './app.vue';
import router from './router'
import Login from './Components/Login.vue';

createApp(App).use(router)
.component('Login',Login)
.mount('#app')
