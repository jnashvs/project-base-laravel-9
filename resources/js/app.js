import './bootstrap';
import { createApp } from 'vue'
import Welcome from './components/Welcome'
import Dropzone from './backoffice-components/file-management/Dropzone'
import FileTypes from './backoffice-components/file-management/FileTypes'
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const app = createApp({})

app.component('welcome', Welcome)
app.component('dropzone-component', Dropzone)
app.component("EasyDataTable", Vue3EasyDataTable);
app.component("filetypes-component", FileTypes);



app.mount('#app')
