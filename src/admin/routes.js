const Home = require('./components/Home.vue');
const UserHome = require('./components/UserHome.vue');
export const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home',
        props: true,
    },
    {
        path: '/users/:user_id',
        component: UserHome,
        name: 'user_home',
        props: true
    }
];