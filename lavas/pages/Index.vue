<template>

<div>

    <div v-if="homeNavValue === 'dashboard'" class="page-index" style="margin-top:30%;">
      <div>
        <mu-raised-button class="demo-raised-button" label="登录" icon="person" backgroundColor="#a4c639"
        @click="doUserlogin"
        v-if="global_userinfo.isLogin==false"
        />
        <mu-raised-button class="demo-raised-button" :label="global_userinfo.realname ||global_userinfo.nickname || global_userinfo.wxnickname || '(昵称)'" icon="person" primary
        v-if="global_userinfo.isLogin==true"
        />
        <br>
        <mu-raised-button class="demo-raised-button" label="统计" icon="trending_up" secondary
        to="dashboard"
        />
        <br>
        <mu-raised-button class="demo-raised-button" label="系统配置" icon="settings" to="settings/settings"/>
        <!-- <br>
        <mu-raised-button class="demo-raised-button" label="TEST" icon="help"
        to="test"
        /> -->
      </div>
    </div>

    <div v-if="homeNavValue === 'task'" class="page-index" style="margin-top:30%;">
      <div>
        <mu-raised-button class="demo-raised-button" label="用户" icon="group" backgroundColor="#a4c639"
        to="user"
        />
        <br>
      </div>
    </div>

    <div v-if="homeNavValue === 'my'">
      <div>
      <mu-card>
        <mu-card-header title="姓名" subTitle="昵称">
          <mu-avatar src="/static/img/logo.png" slot="avatar"/>
        </mu-card-header>
        <mu-card-media title="与您共享" subTitle="很酷的捷径">
          <img src="../assets/images/icon.png" />
        </mu-card-media>
        <mu-card-title title="Content Title" subTitle="Content Title"/>
        <mu-card-text>
          散落在指尖的阳光，我试着轻轻抓住光影的踪迹，它却在眉宇间投下一片淡淡的阴影。
          调皮的阳光掀动了四月的心帘，温暖如约的歌声渐起。
          似乎在诉说着，我也可以在漆黑的角落里，找到阴影背后的阳光，
          找到阳光与阴影奏出和谐的旋律。我要用一颗敏感赤诚的心迎接每一缕滑过指尖的阳光！
        </mu-card-text>
        <mu-card-actions>
          <mu-flat-button label="Action 1"/>
          <mu-flat-button label="Action 2"/>
        </mu-card-actions>
      </mu-card>
      </div>
    </div>

    <mu-paper style="bottom:0;position:fixed;width:100%;max-width: 476px;">
      <mu-bottom-nav :value="homeNavValue" shift @change="handleChange">
        <mu-bottom-nav-item value="dashboard" title="控制台" icon="dashboard"/>
        <mu-bottom-nav-item value="task" title="管理" icon="list"/>
        <mu-bottom-nav-item value="my" title="我" icon="person_pin"/>
      </mu-bottom-nav>
    </mu-paper>
    <mu-toast v-if="toast" :message="toastmessage" @close="hideToast"/>

      <mu-popup position="bottom" popupClass="demo-popup-bottom" :open="bottomPopup" @close="LoginQrclose">
        <mu-appbar title="请用微信扫码登录">
          <mu-flat-button slot="right" label="关闭" color="white" @click="LoginQrclose()"/>
        </mu-appbar>
        <mu-content-block class="page-index">
          <!-- {{global_loginqrcode}} -->
          <div>请保留最长5分钟内</div>
          <img :src='global_loginqrcode' class="demo-popup-bottom"/>
          <!-- <img src="data:image/jpg/png/gif;base64,' . $base64 .'" > -->
        </mu-content-block>
      </mu-popup>
  </div>
</template>

<script>
function setState(store) {
  // this.bottomNav = this.homeNavValue;
}

import Vue from 'vue'

import Mint from 'mint-ui'
import 'mint-ui/lib/style.css'
Vue.use(Mint)

import { Indicator } from 'mint-ui';

import MuseUI from 'muse-ui'
import 'muse-ui/dist/muse-ui.css'
// import 'muse-ui/dist/theme-carbon.css'
Vue.use(MuseUI)

import axios from 'axios';
import { mapState, mapMutations, mapActions } from 'vuex'


export default {
    name: 'index',
    metaInfo: {
        title: 'Home',
        titleTemplate: '%s - Lavas',
        meta: [
            {name: 'keywords', content: 'lavas PWA'},
            {name: 'description', content: '基于 Vue 的 PWA 解决方案，帮助开发者快速搭建 PWA 应用，解决接入 PWA 的各种问题'}
        ]
    },
    computed: {
      ...mapState('basic',[
        'homeNavValue',
        'global_token',
        'global_userinfo',
        'global_loginqrcode',
        'global_pcloginret',
        'global_pclogin_scene'
      ]),

      local_enterprise_tag_global() {
        // const tmptags = this.enterprise_tag_global
        // for (let i = 0; i < tmptags.length; i++) {
        //   tmptags[i].checked = false
        // }
        // return tmptags
      }
    },
    data () {
      return {
        toast:false,
        toastmessage:'',
        bottomPopup: false
      }
    },
    async asyncData({store, route}) {
        setState(store);

        // let result = await axios(`https://query.yahooapis.com/v1/public/yql?q=select%20item.condition%20from%20weather.forecast%20where%20woeid%20%3D%202151849&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys`);
        // let condition = result.data.query.results.channel.item.condition;
        //
        // console.log(`Weather of Shanghai: ${condition.text}, ${condition.temp}°F`);
        // let result_showlist = await axios(`https://ymq.coolje.com/api/club/showlist`);
        // console.log("test api==",result_showlist.data);
    },
    methods: {
      ...mapActions({setHomeNavValue: 'basic/setHomeNavValue'}),
      ...mapActions({doLogin: 'basic/doLogin'}),
      ...mapActions({getUserloginqrcode: 'basic/getUserloginqrcode'}),
      ...mapActions({PC_qrcode_login: 'basic/PC_qrcode_login'}),

      LoginQrOpen () {
        this.bottomPopup = true
        console.log("this.bottomPopup===",this.bottomPopup);
      },
      LoginQrclose () {
        console.log("close---qr");
        this.bottomPopup = false
        if(this.timerhandle){
          clearInterval(this.timerhandle);
        }
      },
      doUserlogin(){
        // var userparams={
        //   name:"pail",
        //   password:"123123"
        // }
        // Indicator.open({
        //   text: '登录中...',
        //   spinnerType: 'fading-circle'
        // });
        // this.doLogin(userparams)

        // this.toastmessage = '登录成功'
        // this.showToast()
        console.log("in do user login");
        this.getUserloginqrcode({});
        this.LoginQrOpen ();

        //启动监测.
        var _this = this;
        this.timeout_int = 0;
        this.timerhandle = setInterval(()=>{
          if(_this.timeout_int>(5*60/2)){
            this.showToastMessage('超时')
            _this.LoginQrclose();
            return;
          }

          _this.timeout_int=_this.timeout_int+1;

          if(_this.global_token==''){
            console.log("_this.global_pcloginret===",_this.global_pcloginret);
            if(_this.global_pcloginret.errorcode){
              var errorcode = _this.global_pcloginret.errorcode;
              if(errorcode=='使用中' || errorcode=='创建'){

              }else{
                var error = _this.global_pcloginret.error;
                _this.showToastMessage(error)
                _this.LoginQrclose();
              }
            }
          }else{
            _this.LoginQrclose();
            return;
          }

          this.PC_qrcode_login({scene:this.global_pclogin_scene});

        },2000);
        /* 刷新查询 */


      },
      refreshCheckqrcode(){
      },
      handleChange (val) {
        this.setHomeNavValue(val)
      },
      showToastMessage (messagenote) {
        this.toastmessage = messagenote
        this.toast = true
        if (this.toastTimer) clearTimeout(this.toastTimer)
        this.toastTimer = setTimeout(() => { this.toast = false }, 2000)
      },
      showToast () {
        Indicator.close()
        this.toastmessage = '登录成功'
        this.toast = true
        if (this.toastTimer) clearTimeout(this.toastTimer)
        this.toastTimer = setTimeout(() => { this.toast = false }, 2000)
      },
      hideToast () {
        this.toast = false
        if (this.toastTimer) clearTimeout(this.toastTimer)
      }
    },
    mounted(){

    },
    created() {

    },
    watch:{
      'global_token': {

        handler:'showToast'
      }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');

.page-index {
  /* background-color: #EEEFF0; */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  justify-content:center;
  align-content:center;
}
.demo-raised-button {
  margin: 12px;
  width: 200px;
}
.demo-popup-bottom {
  width: 230px;
}
</style>
