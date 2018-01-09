import Vue from 'vue'
import Vuex from 'vuex'
import AuthUser from './modules/auth-user'

Vue.use(Vuex);
const state = {
    me: null,
    menus: null,
    users: null
};
//处理状态(数据)变化,也就是写对状态数据的操作的方法，相当于vue实例化中的methods
const mutations = {
        UPDATE_ME (state, me) {
            state.me = me;
        },
        UPDATE_MENUS (state, menus) {
            state.menus = menus;
        },
        allUser(state, users){
            state.users = users;
        }
};
export default new Vuex.Store({
    state,
    mutations,
    modules:{
        AuthUser
    }
})
