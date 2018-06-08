<template>
<div>
  <mu-appbar title="用户" style="position:fixed;">
    <mu-flat-button label="用户" icon="arrow_back" slot="left" @click.native="$router.go(-1)" replace/>
    <mu-icon-button icon="expand_more" slot="right" ref="buttonExportRef" @click="toggle"/>
    <mu-popover :trigger="trigger" :open="open" @close="handleClose">
      <mu-menu>
        <mu-menu-item title="导出" @click="exportMenuItemClick" class="demo-menu" rightIcon="get_app"/>
      </mu-menu>
    </mu-popover>
  </mu-appbar>

  <div class="demo-refresh-container">
    <br/><br/><hr/>
    <!-- <mu-refresh-control :refreshing="refreshing" :trigger="refreshtrigger" @refresh="refreshlist"/> -->
    <mu-list>
    <mu-sub-header style="position:fixed;">所有用户-共{{global_userlist.total}},第{{global_userlist.current_page}}页</mu-sub-header>
    <br/>
      <template v-for="item in formatedUserlist">
        <mu-list-item :title="item.realname ||item.nickname || item.wxnickname || '(昵称)'">
          <mu-avatar :src="item.iconurl || item.wxiconurl || '../assets/images/logo.png'" slot="leftAvatar"/>
          <span slot="describe">
            <span style="color: rgba(0, 0, 0, .87)">{{item.workingcompany||'(公司)'}}</span> <br/>
            {{item.workingcompanyrole||'(公司角色)'}}
          </span>
          <mu-icon-menu slot="right" icon="more_vert" tooltip="操作">
            <mu-menu-item title="标记" />
            <mu-menu-item title="删除" />
          </mu-icon-menu>
        </mu-list-item>
        <mu-divider inset/>
      </template>
  </mu-list>


  </div>
  <mu-raised-button class="demo-raised-button" label="加载更多" icon="hourglass_empty" fullWidth
  @click="loadMore"
  />
  <!-- <mu-infinite-scroll :scroller="scroller" :loading="loading" @load="loadMore"/> -->
  <mu-toast v-if="toast" :message="toastmessage"/>

</div>
</template>

<script>
function setState(store) {}

import Vue from 'vue'

// import Mint from 'mint-ui'
// import 'mint-ui/lib/style.css'
// Vue.use(Mint)
//
// import { Indicator } from 'mint-ui';

import MuseUI from 'muse-ui'
import 'muse-ui/dist/muse-ui.css'
// import 'muse-ui/dist/theme-carbon.css'
Vue.use(MuseUI)

import { mapState, mapMutations, mapActions } from 'vuex'


import axios from 'axios';

export default {
    name: 'dashboard',
    metaInfo: {
        title: '控制台',
        titleTemplate: '控制台',
        meta: [
        ]
    },
    computed: {
      ...mapState('basic',[
        'global_token'
      ]),
      ...mapState('user',[
        'global_userlist'
      ]),

      formatedUserlist() {
        // const tmptags = this.enterprise_tag_global
        // for (let i = 0; i < tmptags.length; i++) {
        //   tmptags[i].checked = false
        // }
        console.log("this.global_userlist==",this.global_userlist);
        // this.loading = false
        return this.global_userlist.data
      }
    },
    data () {
      return {
        open: false,
        trigger: null,
        refreshing:false,
        refreshtrigger: null,
        loading: false,
        scroller: null,
        page:1,
        toastmessage:'',
        toast:false
      }
    },
    async asyncData({store, route}) {
        setState(store);

    },
    mounted () {
      this.trigger = this.$refs.buttonExportRef.$el
      // this.refreshtrigger = this.$el
      this.scroller = this.$el
      this.getUserlist({token:this.global_token,page:this.page})
    },
    methods: {
      ...mapActions({getUserlist: 'user/getUserlist'}),

      toggle () {
        this.open = !this.open
      },
      handleChange (val) {
        this.bottomNav = val
      },
      exportMenuItemClick(e){
        console.log("exportMenuItemClick===",e);
        this.open = false
      },
      handleClose (e) {
        this.open = false
      },
      refreshlist () {
        this.refreshing = true

      },
      showToast () {
        this.toastmessage = '没有更多'
        this.toast = true
        if (this.toastTimer) clearTimeout(this.toastTimer)
        this.toastTimer = setTimeout(() => { this.toast = false }, 1000)
      },
      loadMore () {
        this.loading = true
        this.page = this.page+1;
        if(this.global_userlist.last_page < this.page){
          this.showToast()
          return;
        }
        this.getUserlist({token:this.global_token,page:this.page})
      }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
/* .file-button{
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  opacity: 0;
}

.demo-raised-button-container{
  display: flex;
  align-items: center;
  flex-wrap: wrap;
}
.demo-raised-button {
  margin: 12px;
} */
.demo-refresh-container{
  width: 100%;
  height: 100%;
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border: 1px solid #d9d9d9;
  position: relative;
  user-select: none;
}

</style>
