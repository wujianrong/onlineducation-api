<template>
    <div class="item el-col-24">
        <el-menu :default-active="activeindex" class="el-menu-demo" mode="horizontal" @select="handleSelect" router>
            <div v-for="menu in menus">
                <el-menu-item :index="menu.id" :route="{name: menu.url}">{{menu.name}}</el-menu-item>
            </div>
        </el-menu>
    </div>
</template>
<script>
    export default{
        data() {
            return {

            };
        },
        methods: {
            handleSelect(key, keyPath) {
                //console.log(key, keyPath);
            },
            //打印测试数据，调试使用
            consoletest() {
                //console.log('用户信息')
                //console.log(this.me)
                console.log('菜单数据')
                console.log(this.menus)
                console.log('当前路由信息')
                console.log(this.$route)
            }
        },
        computed:{
            me () {
                return this.$store.state.me ? this.$store.state.me : {};
            },
            menus () {
                return this.$store.state.menus.data ? this.$store.state.menus.data : {};
            },
            activeindex () {
                return this.$route.meta.pindex ? this.$route.meta.pindex: '';
            }
        },
        mounted () {
            //this.consoletest()
        }
    }
</script>
<style scoped>

    .el-menu {
        background-color: #3C98FF;
    }
    .el-menu-item {
        color: #C1DFFF;
    }
    .el-menu-item.is-active {
        color: #F2F8FF;
        background-color: #4FA2FF;
        font-weight: 600;
    }
    .el-menu--horizontal>.el-menu-item {
        border-bottom: none !important;
    }
    .el-menu--horizontal .el-menu-item:hover {
        color: #fff;
        background: #4FA2FF;
    }
</style>