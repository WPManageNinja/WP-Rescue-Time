import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

import 'element-ui/lib/theme-default/table.css'
import 'element-ui/lib/theme-default/loading.css'

import {
    Table, TableColumn, Icon, Loading
} from 'element-ui';

import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

// configure language
locale.use(lang);

Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Loading);
Vue.use(Icon);

import {routes} from './routes'

import Application from './App.vue'

const router = new VueRouter({
    routes,
    linkActiveClass: 'active'
});


Application.router = router;
window.ninjaApp = new Vue(Application).$mount('#rescure_time_app');


