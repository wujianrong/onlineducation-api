import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);
const parentComponent = {
	template: '<router-view></router-view>'
};
export default new Router({
	//mode: 'history',
	routes: [{
			path: '*',
			redirect: {
				name: 'admin'
			}
		},
		//pindex用于定位当前页面的父级菜单，index用于定位当前页面的子级菜单（active），结合状态管理器中返回来的菜单数据中的id使用
		//pindex和index对应的就是菜单数据中的parent_id和id
		{
			path: '',
			name: 'admin',
			component: require('../components/admin/Admin.vue'),
			meta: {
				requireAuth: false,
				pindex: '1',
				index: '1'
			},
			children: [
				// 主页
				{
					path: 'home',
					name: 'home',
					title: '主页',
					component: require('../components/admin/home/home.vue'),
					meta: {
						requireAuth: false,
						pindex: '1',
						index: '2'
					}
				},
				//教务
				{
					path: '',
					// name: 'teachtask',
					component: parentComponent,
					meta: {
						requireAuth: false
					},
					children: [
						//课程管理（默认进入teachtask路径后就跳入课程管理）
						{
							path: '',
							name: 'teachtasks',
							component: parentComponent,
							meta: {
								requireAuth: false,
								title: '课程管理',
								pindex: '3',
								index: '4'
							},
							children: [{
									path: 'teachtask',
									name: 'teachtask',
									component: require('../components/admin/teachtask/classAdminister.vue'),
									meta: {
										requireAuth: false,
										title: '课程管理',
										pindex: '3',
										index: '4'
									}
								},
								{
									path: 'addtask',
									name: 'addtask',
									component: require('../components/admin/teachtask/classAdminister/addtask.vue'),
									meta: {
										requireAuth: false,
										title: '新建课程',
										pindex: '3',
										index: '4'
									}
								}
							]
						},
						//视频库
						{
							path: 'videos',
							name: 'videos',
							component: require('../components/admin/teachtask/videos.vue'),
							meta: {
								requireAuth: false,
								title: '视频库',
								pindex: '3',
								index: '5'
							}
						},
						//栏目管理
						{
							path: 'colment',
							name: 'colment',
							component: require('../components/admin/teachtask/ColMent.vue'),
							meta: {
								requireAuth: false,
								title: '栏目管理',
								pindex: '3',
								index: '5'
							}
						},
						//分类管理
						{
							path: 'sortment',
							name: 'sortment',
							component: require('../components/admin/teachtask/SortMent.vue'),
							meta: {
								requireAuth: false,
								title: '分类管理',
								pindex: '3',
								index: '5'
							}
						},
						//评论管理
						{
							path: 'revment',
							name: 'revment',
							component: require('../components/admin/teachtask/RevMent.vue'),
							meta: {
								requireAuth: false,
								title: '评论管理',
								pindex: '3',
								index: '5'
							}
						},
					]
				},
				//运营
				{
					path: 'operative',
					// name: 'teachtask',
					component: parentComponent,
					meta: {
						requireAuth: false
					},
					children: [
						//站内通知
						{
							path: '',
							name: 'operative',
							component: require('../components/admin/operative/OperativeNotify.vue'),
							meta: {
								requireAuth: false,
								title: '站内通知',
								pindex: '8',
								index: '4'
							}
						},
						//广告通知
						{
							path: 'adalert',
							name: 'adalert',
							component: require('../components/admin/operative/AdAlert.vue'),
							meta: {
								requireAuth: false,
								title: '广告通知',
								pindex: '8',
								index: '5'
							}
						},
						//VIP会员管理
						{
							path: 'vipment',
							name: 'vipment',
							component: require('../components/admin/operative/VipMent.vue'),
							meta: {
								requireAuth: false,
								title: 'VIP会员管理',
								pindex: '8',
								index: '5'
							}
						},
						//讲师信息管理
						{
							path: 'instment',
							name: 'instment',
							component: require('../components/admin/operative/InstMent.vue'),
							meta: {
								requireAuth: false,
								title: '讲师信息管理',
								pindex: '8',
								index: '5'
							}
						},
						//主页信息管理
						{
							path: 'hoinfment',
							name: 'hoinfment',
							component: require('../components/admin/operative/HoinfMent.vue'),
							meta: {
								requireAuth: false,
								title: '主页信息管理',
								pindex: '8',
								index: '5'
							}
						},
					]
				},
				// 用户
				{
					path: 'user',
					name: 'user',
					component: require('../components/admin/user/UserMent.vue'),
					meta: {
						requireAuth: false,
						title: '用户管理',
						pindex: '14',
						index: '2'
					}
				},
				// 订单
				{
					path: 'order',
					name: 'order',
					component: require('../components/admin/order/OrderMent.vue'),
					meta: {
						requireAuth: false,
						title: '订单管理',
						pindex: '16',
						index: '2'
					}
				},
				//系统设置
				{
					path: 'rbacment',
					// name: 'teachtask',
					component: parentComponent,
					meta: {
						requireAuth: false
					},
					children: [
						//权限管理
						{
							path: '',
							name: 'rbacment',
							component: require('../components/admin/systemset/RbacMent.vue'),
							meta: {
								requireAuth: false,
								title: '权限管理',
								pindex: '18',
								index: '4'
							}
						},
						//角色管理
						{
							path: 'rolement',
							name: 'rolement',
							component: require('../components/admin/systemset/RoleMent.vue'),
							meta: {
								requireAuth: false,
								title: '角色管理',
								pindex: '18',
								index: '5'
							}
						},
						//账号管理
						{
							path: 'accment',
							name: 'accment',
							component: require('../components/admin/systemset/AccMent.vue'),
							meta: {
								requireAuth: false,
								title: '账号管理',
								pindex: '18',
								index: '5'
							}
						},
						//教务设置
						{
							path: 'eduset',
							name: 'eduset',
							component: require('../components/admin/systemset/EduSet.vue'),
							meta: {
								requireAuth: false,
								title: '教务设置',
								pindex: '18',
								index: '5'
							}
						},
						//系统操作日志
						{
							path: 'syslog',
							name: 'syslog',
							component: require('../components/admin/systemset/SysLog.vue'),
							meta: {
								requireAuth: false,
								title: '系统操作日志',
								pindex: '18',
								index: '5'
							}
						},
					]
				},
			]
		},
		{
			path: '/login',
			name: 'login',
			component: require('../components/admin/login/login.vue'),
			meta: {
				requireAuth: false
			}
		}
		// {
		//     path: '/profile',
		//     name: 'profile',
		//     component: require('../components/Profile.vue'),
		//     meta:{requireAuth : true}
		// }
	]
})