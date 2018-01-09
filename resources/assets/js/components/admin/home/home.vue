<template>
	<el-container>
		<el-header class="welcome_title">
			欢迎使用，供应链微课堂管理系统
		</el-header>
		<el-main>
			<img src="/images/welcome.png" class="welcome" />
		</el-main>
	</el-container>
</template>

<script>
	export default {
		components: {

		}
	}
</script>
<style>
	.welcome {
		width: 100%;
	}
	
	.welcome_title {
		font-weight: bold;
		color: #4ea4d4;
		font-size: 30.0px;
		text-align: center;
		margin-top: 50px;
	}
</style>