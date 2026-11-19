/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;
import common from "./common";
import { createApp } from "vue";
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";
import PrimeVue from "primevue/config";
import ConfirmationService from "primevue/confirmationservice";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import ConfirmDialog from "primevue/confirmdialog";
import ToastService from "primevue/toastservice";
import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";
// import 'primeicons/primeicons.css';
import Toast from "primevue/toast";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import VPagination from "@hennge/vue3-pagination";
import "@hennge/vue3-pagination/dist/vue3-pagination.css";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Toolbar from "primevue/toolbar";
import Dropdown from "primevue/dropdown";
import Calendar from 'primevue/calendar';
import store from "./store";
import dashboard from "./apis/dashboard";
import Tooltip from 'primevue/tooltip';
import MultiSelect from 'primevue/multiselect';
import Checkbox from 'primevue/checkbox';
import Message from 'primevue/message';
import ProgressBar from 'primevue/progressbar';
import RadioButton from 'primevue/radiobutton';
const app = createApp({});
app.mixin(common);
app.use(PrimeVue);
app.use(ConfirmationService);
app.use(ToastService);
app.directive('tooltip', Tooltip);

// app.mixin(dashboard);
app.config.globalProperties.$env_Url = process.env.MIX_APP_URL;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
app.component(
    "dashboard-component",
    require("./components/dashboard/Dashboard.vue").default
);
app.component(
    "emp_dashboard-component",
    require("./components/employeepage/dashboard.vue").default
);
app.component(
    "create_user-component",
    require("./components/user/create_user.vue").default
);
app.component(
    "index_user-component",
    require("./components/user/index.vue").default
);
app.component(
    "edit_user-component",
    require("./components/user/edit_user.vue").default
);

app.component(
    "create_announcement-component",
    require("./components/announcement/create_announcement.vue").default
);
app.component(
    "index_announcement-component",
    require("./components/announcement/index_announcement.vue").default
);
app.component(
    "edit_announcement-component",
    require("./components/announcement/edit_announcement.vue").default
);
app.component(
    "report_announcement-component",
    require("./components/announcement/report.vue").default
);

app.component(
    "create_shutdownapplication-component",
    require("./components/shutdownapplication/create_shutdownapplication.vue")
        .default
);
app.component(
    "index_shutdownapplication-component",
    require("./components/shutdownapplication/index_shutdownapplication.vue")
        .default
);
app.component(
    "edit_shutdownapplication-component",
    require("./components/shutdownapplication/edit_shutdownapplication.vue")
        .default
);

app.component(
    "create_application-component",
    require("./components/application/create_application.vue")
        .default
);
app.component(
    "index_application-component",
    require("./components/application/index_application.vue")
        .default
);
app.component(
    "edit_application-component",
    require("./components/application/edit_application.vue")
        .default
);

app.component(
    "create_role-component",
    require("./components/role/create_role.vue").default
);
app.component(
    "index_role-component",
    require("./components/role/index_role.vue").default
);
app.component(
    "edit_role-component",
    require("./components/role/edit_role.vue").default
);

app.component(
    "create_module-component",
    require("./components/module/create_module.vue").default
);
app.component(
    "index_module-component",
    require("./components/module/index_module.vue").default
);
app.component(
    "edit_module-component",
    require("./components/module/edit_module.vue").default
);

app.component(
    "create_permission-component",
    require("./components/permission/create_permission.vue").default
);
app.component(
    "index_permission-component",
    require("./components/permission/index_permission.vue").default
);
app.component(
    "edit_permission-component",
    require("./components/permission/edit_permission.vue").default
);

app.component(
    "index_log-component",
    require("./components/log/index_log.vue").default
);

app.component(
    "index_roleaccessright-component",
    require("./components/roleaccessright/index_roleaccessright.vue").default
);

app.component(
    "index_database-component",
    require("./components/database/index_database.vue").default
);

app.component(
    "login-component",
    require("./components/auth/login.vue").default
);

app.component(
    "forgot_password-component",
    require("./components/auth/forgot_password.vue").default
);

app.component(
    "change_password-component",
    require("./components/auth/change_password.vue").default
);

app.component("Datepicker", Datepicker);
app.component("Dialog", Dialog);
app.component("ConfirmationService", ConfirmationService);
app.component("Button", Button);
app.component("ConfirmDialog", ConfirmDialog);
app.component("Toast", Toast);
app.component("Accordion", Accordion);
app.component("AccordionTab", AccordionTab);
app.component("v-pagination", VPagination);
app.component("DataTable", DataTable);
app.component("Column", Column);
app.component("InputText", InputText);
app.component("Toolbar", Toolbar);
app.component("Dropdown", Dropdown);
app.component("Calendar", Calendar);
app.component("MultiSelect", MultiSelect);
app.component("Checkbox", Checkbox);
app.component("Message", Message);
app.component("RadioButton", RadioButton);
// app.component("ProgressBar", ProgressBar);



app.use(store);
app.mount("#app");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//  import create_user from './components/user/create_user.vue';

// const app = new Vue({
//     el: '#app',

// });
