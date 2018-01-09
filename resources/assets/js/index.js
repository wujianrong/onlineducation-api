
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
//
import Vue from 'vue'
// import VueRouter from 'vue-router'
import router from './router/index-routes.js'
import store from './store/index.js'
import App from './components/App'

import VeeValidate, { Validator } from 'vee-validate';
import zh_CN from 'vee-validate/dist/locale/zh_CN';
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize(zh_CN);

Vue.config.productionTip = false;
// Vue.use(VueRouter);
// Install the Plugin and set the locale.
Vue.use(VeeValidate, {
    locale: 'zh_CN'
});

// Vue.component('app',App);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
router.beforeEach((to, from, next) => {
    console.log(to.meta.requireAuth)
if(to.meta.requireAuth){
    if(store.state.authenticated){
        next();
    }else{
        return next({'name':'login'})
    }
}else {
    next();
}
})
new Vue({
    el: '#app',
    store,
    router,
    template: '<App/>',
    components: { App }
})
