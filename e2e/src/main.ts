import './assets/styles/main.scss'
import '@fortawesome/fontawesome-free/css/all.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import pinia from './stores'
import { setupVant } from './plugins/vant'
import VueApexCharts from 'vue3-apexcharts'

const app = createApp(App)

app.use(router)
app.use(pinia)
app.use(VueApexCharts)
setupVant(app)

app.mount('#app')
