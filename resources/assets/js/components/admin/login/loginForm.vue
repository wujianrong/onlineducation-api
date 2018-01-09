<template>
  <div class='loginBox el-col-24'>
  	<div class="login_main">
  		<div class="hr_logo">
        	<div class="hr_img">
        		<img src="/images/logo.png" class="img-responsive">
        	</div>
        	<div class="hr_p">
        		<p class="p1">供应链微课堂管理系统</p>
        		<p class="p2">SUPPLY-CHAIN CLASS SYSTEM</p>
        	</div>
        </div>
    <div class='login el-col-4'>
      <h3>系统登陆</h3>
      <div class="form">
        <el-row>
            <el-col :span="20" :offset="2">
                <el-input v-model="userName" placeholder="请输入用户名" prefix-icon="el-icon-erp-icon-account"></el-input>
            </el-col>
            <el-col :span="20" :offset="2">
              <el-input @keyup.enter.native="login()" v-model="userPsw" type="password" placeholder="请输入密码" prefix-icon="el-icon-erp-icon-lock"></el-input>
            </el-col>
          <el-col :span="12" :offset="6">
            <el-button type="primary" @click="login()" class="el-col-24" :loading="this.isLoading">登录</el-button>
          </el-col>
        </el-row>
      </div>
    </div>
  	</div>

  </div>
</template>

<script type="text/ecmascript-6">
    import JWTToken from '../../helpers/jwt'
    export default{
        data() {
            return {
                userName: '',
                userPsw: '',
                isLoading: false
            }
        },
        methods: {

            login(){//登陆
            	this.isLoading = true;
                let formData = {
                    name: this.userName,
                    password: this.userPsw
                };

                axios.post('/api/login', formData).then(response => {//获取access_token
                	this.isLoading = false;
                    console.log(response.data);
                    JWTToken.setToken(response.data.token);
                    this.$store.state.authenticated = true;
                    this.$router.push({ 'name' :'profile'})
                }).catch(error => {
                	this.isLoading = false;
                    console.log(error.response)
                })
            }
        }
    }
</script>

<style>
.login_main{
    position: absolute;
    margin: auto;
    top: 0;
    bottom: 100px;
    left: 0;
    right: 0;
    width: 460px;
    height: 570px;
    border-radius: 10px;
}
.hr_logo {
    display: inline-block;
    margin-bottom: 60px;
    width: 460px;
}
.hr_logo .hr_img {
    position: relative;
    width: 174px;
    float: left;
}
.hr_logo .hr_img:after {
    content: '';
    position: absolute;
    width: 1px;
    height: 100%;
    background-color: #f2f8ff;
    top: 0;
    right: -44px;
}
.hr_p {
    float: right;
}
.hr_p .p1 {
    font-size: 16px;
}
.hr_p .p2 {
    font-size: 12px;
}
.hr_p .p1, .hr_p .p2 {
    margin: 0 0 2px;
    color: #FFFFFF;
}
.img-responsive{
    display: block;
    max-width: 100%;
    height: auto;
}
.login{
    min-width: 460px;
    overflow: hidden;
    height: 320px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 3px #e0e0e0;
}
.el-input{
  margin-left:0;
}
.login h3{
  margin: 40px 0;
  font-size: 15px;
  color: #444;
  text-align: center;
  color: #459AFF;
}
.el-col{
  margin-bottom:15px;
}
.el-button{
  margin-top:15px;
}

@keyframes animatedBackground {
  from { background-position: 0 0; }
  to { background-position: 100% 0; }
}
@-webkit-keyframes animatedBackground {
  from { background-position: 0 0; }
  to { background-position: 100% 0; }
}
.loginBox{
  position: relative;
  width: 100%;
  height: 100%;
  background-color: white;
  background-position: 0px 0px;
  background-repeat: repeat-x;
  background-image: url(/images/login_bg.png);
  animation: animatedBackground 40s linear infinite;
  -ms-animation: animatedBackground 40s linear infinite;
  -moz-animation: animatedBackground 40s linear infinite;
  -webkit-animation: animatedBackground 40s linear infinite;
}
</style>