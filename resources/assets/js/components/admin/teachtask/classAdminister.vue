<template>
	<div>
		<div class="admin_title">
			{{$route.meta.title}}
		</div>
		<el-tabs v-model="activeName2" type="card" class="padding-top">
			<el-tab-pane label="课程管理" name="first">
				<classadtable></classadtable>
			</el-tab-pane>
		</el-tabs>
	</div>

</template>

<script>
	import classadtable from './../teachtask/classAdminister/classAdministertable.vue'
	export default {
		components: {
			classadtable
		},
		data() {
			return {
				activeName2: 'first'
			}
		},
		mounted: function() {
			//      	console.log(this.$route)
		}
	}
</script>