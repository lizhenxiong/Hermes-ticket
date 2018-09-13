import NotFound from '../components/common/404.vue';
import Main from '../components/common/main.vue';

let routes = [
    {
        path: '/404',
        component: NotFound,
        name: '',
        hidden: true
    },
    {
        path: '/main',
        component: Main,
        name: '导航一'
    },
];

export default routes;