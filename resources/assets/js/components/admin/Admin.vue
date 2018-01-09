<template>
         <div class="home">
             <leftnav></leftnav>
            <div class="content">
                <topmenu></topmenu>
                <div class="info_box">
                    <router-view></router-view>
                </div>
            </div>
        </div>
</template>

<script>
    import topmenu from '../common/TopMenu.vue'
    import leftnav from '../common/LeftNav.vue'
    export default{
        data () {
            return {
            }
        },
        components: {
            topmenu,leftnav
        },
        methods: {
        },
        computed: {

        }
    }
</script>
<style>
    .home>.content{
        padding-left: 219px;
    }
    .info_box{
        padding: 10px 15px;
        overflow: hidden;
    }
</style>