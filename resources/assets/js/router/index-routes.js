 



import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);
const parentComponent = {template: '<router-view></router-view>'};
export default new Router({
    mode: 'history',
    routes: [
        {
            path: '*',
            redirect: {name: 'item'}
        },
        {
            path: '/item',
            name: 'item',
            component: require('../components/index/Home.vue'),
            meta:{requireAuth : false}
        },
        // {
        //     path: '/login',
        //     name: 'login',
        //     component: require('../components/index/login/login.vue'),
        //     meta:{requireAuth : false}
        // },
        // {
        //     path: '/profile',
        //     name: 'profile',
        //     component: require('../components/Profile.vue'),
        //     meta:{requireAuth : true}
        // }
    ]
})


