/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
//
import Vue from 'vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import router from './router/admin-routes.js'
import store from './store/index.js'
import App from './components/App'
import './admin.css'
import VeeValidate, {
	Validator
} from 'vee-validate';
import zh_CN from 'vee-validate/dist/locale/zh_CN';
import VueEditor from 'vue2-editor' //富文本编辑器
import DataTables from 'vue-data-tables' //数据表格
import uploader from 'vue-simple-uploader'
import YDUI from 'vue-ydui';
import Scrollactive from 'vue-scrollactive';
// Localize takes the locale object as the second argument (optional) and merges it.
Validator.localize(zh_CN);

Vue.config.productionTip = false;
// Vue.use(VueRouter);
// Install the Plugin and set the locale.
Vue.use(ElementUI)
Vue.use(VueEditor)
Vue.use(DataTables)
Vue.use(uploader)
Vue.use(YDUI)
Vue.use(Scrollactive)
Vue.use(VeeValidate, {
	locale: 'zh_CN'
});

let userdata = {
	data: {
		name: 'admin',
		disname: '超级管理员'
	}
}

function getMe() {

	store.commit('UPDATE_ME', userdata.data);
}

function getUsers() {
	// store.commit('allUser', res.data.data);
}
let menusdata = {
	data: [{
			id: '1',
			name: '主页',
			parent_id: '0',
			icon: '',
			url: 'home',
			description: '主页',
			chlidren: [{
				id: '2',
				name: '后台主页',
				parent_id: '1',
				icon: 'el-icon-erp-icon-home',
				url: 'home',
				description: '后台主页'
			}]
		},

		{
			id: '3',
			name: '教务',
			parent_id: '0',
			icon: '',
			url: 'teachtask',
			description: '教务',
			chlidren: [{
					id: '4',
					name: '课程管理',
					parent_id: '3',
					icon: 'el-icon-erp-icon-ic_school',
					url: 'teachtasks',
					description: '课程管理'
				},
				{
					id: '5',
					name: '视频库',
					parent_id: '3',
					icon: 'el-icon-erp-icon-youtube-play',
					url: 'videos',
					description: '视频库'
				},
				{
					id: '6',
					name: '栏目管理',
					parent_id: '3',
					icon: 'el-icon-erp-icon-dns',
					url: 'colment',
					description: '栏目管理'
				},
				{
					id: '7',
					name: '分类管理',
					parent_id: '3',
					icon: 'el-icon-erp-icon-widgets',
					url: 'sortment',
					description: '分类管理'
				},
				{
					id: '8',
					name: '评论管理',
					parent_id: '3',
					icon: 'el-icon-erp-icon-message-processing',
					url: 'revment',
					description: '评论管理'
				}
			]
		},
		{
			id: '8',
			name: '运营',
			parent_id: '0',
			icon: '',
			url: 'operative',
			description: '运营',
			chlidren: [{
					id: '9',
					name: '站内通知',
					parent_id: '8',
					icon: 'el-icon-erp-icon-ic_forum',
					url: 'operative',
					description: '站内通知'
				}, {
					id: '10',
					name: '广告通知',
					parent_id: '8',
					icon: 'el-icon-erp-icon-image',
					url: 'adalert',
					description: '广告通知'
				},
				{
					id: '11',
					name: 'VIP会员管理',
					parent_id: '8',
					icon: 'el-icon-erp-icon-vimeo',
					url: 'vipment',
					description: 'VIP会员管理'
				},
				{
					id: '12',
					name: '讲师信息管理',
					parent_id: '8',
					icon: 'el-icon-erp-icon-account-plus',
					url: 'instment',
					description: '讲师信息管理'
				},
				{
					id: '13',
					name: '主页信息管理',
					parent_id: '8',
					icon: 'el-icon-erp-icon-ic_important_devices',
					url: 'hoinfment',
					description: '主页信息管理'
				}
			]
		},
		{
			id: '14',
			name: '用户',
			parent_id: '0',
			icon: '',
			url: 'user',
			description: '用户',
			chlidren: [{
				id: '15',
				name: '用户管理',
				parent_id: '14',
				icon: 'el-icon-erp-icon-yonghu',
				url: 'user',
				description: '用户管理'
			}]
		},
		{
			id: '16',
			name: '订单',
			parent_id: '0',
			icon: '',
			url: 'order',
			description: '订单',
			chlidren: [{
				id: '17',
				name: '订单管理',
				parent_id: '16',
				icon: 'el-icon-erp-icon-clipboard-text',
				url: 'order',
				description: '订单管理'
			}]
		},
		{
			id: '18',
			name: '系统设置',
			parent_id: '0',
			icon: '',
			url: 'rbacment',
			description: '系统设置',
			chlidren: [{
					id: '19',
					name: '权限管理',
					parent_id: '18',
					icon: 'el-icon-erp-icon-lock',
					url: 'rbacment',
					description: '权限管理'
				},
				{
					id: '20',
					name: '角色管理',
					parent_id: '18',
					icon: 'el-icon-erp-icon-ic_contacts',
					url: 'rolement',
					description: '角色管理'
				},
				{
					id: '21',
					name: '账号管理',
					parent_id: '18',
					icon: 'el-icon-erp-icon-account-multiple',
					url: 'accment',
					description: '账号管理'
				},
				{
					id: '22',
					name: '教务设置',
					parent_id: '18',
					icon: 'el-icon-erp-icon-message-video',
					url: 'eduset',
					description: '账号管理'
				},
				{
					id: '23',
					name: '系统操作日志',
					parent_id: '18',
					icon: 'el-icon-erp-icon-ic_touch_app',
					url: 'syslog',
					description: '系统操作日志'
				}
			]
		}
	]
}

function getMenu(next) {
	store.commit('UPDATE_MENUS', menusdata);
}

router.beforeEach((to, from, next) => {
	getMe()
	getMenu()
	console.log(to.meta.requireAuth);
	if(to.meta.requireAuth) {
		if(store.state.authenticated) {
			next();
		} else {
			return next({
				'name': 'login'
			})
		}
	} else {
		next();
	}
})
new Vue({
	el: '#app',
	store,
	router,
	template: '<App/>',
	components: {
		App
	}
})