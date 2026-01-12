import './bootstrap';
import { createApp } from 'vue';
import Home from './Components/Home.vue';
import '@mdi/font/css/materialdesignicons.css';

// Vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import '@syncfusion/ej2-base/styles/material.css';
import '@syncfusion/ej2-buttons/styles/material.css';
import '@syncfusion/ej2-calendars/styles/material.css';
import '@syncfusion/ej2-schedule/styles/material.css';

const vuetify = createVuetify();

createApp(Home)
    .use(vuetify)
    .mount('#app');
