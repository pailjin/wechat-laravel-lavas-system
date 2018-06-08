<template>
  <div class="demo-grid">
    <mu-appbar title="设置">
      <mu-icon-button icon="arrow_back" slot="left" to="/" replace/>
      <!-- <mu-flat-button label="expand_more" slot="right" @click.native="$router.go(-1)"/>
      <mu-icon-button icon="expand_more" slot="right"/> -->
    </mu-appbar>

    <mu-list>
      <mu-list-item title="支付信息配置" to="paymentsettings">
        <mu-icon slot="left" value="inbox"/>
      </mu-list-item>
      <mu-list-item title="积分配置" to="pointsettings">
        <mu-icon slot="left" value="grade"/>
      </mu-list-item>
      <mu-list-item title="积分类型配置" to="pointtypesettings">
        <mu-icon slot="left" value="grade"/>
      </mu-list-item>
    </mu-list>
  </div>
</template>

<script>
function setState(store) {}

import Vue from 'vue'
import MuseUI from 'muse-ui'
import 'muse-ui/dist/muse-ui.css'
// import 'muse-ui/dist/theme-carbon.css'
Vue.use(MuseUI)

import axios from 'axios';

export default {
    name: 'dashboard',
    metaInfo: {
        title: '控制台',
        titleTemplate: '控制台',
        meta: [
        ]
    },
    data () {
      return {
        bottomNav: 'recents',
        value: '1',
        activeTab: 'tab1'
      }
    },
    async asyncData({store, route}) {
        setState(store);

    },
    methods: {
      handleChange (val) {
        // this.bottomNav = val
        this.value = val
      },

      handleTabChange(val) {
        this.activeTab = val
      }
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
  .cus-tabs {
    background: none;
  }
  .cus-tab-text-style {
    color: #000;
  }
  .cus-tab-text-style .mu-tab-text {
    color: #000;
  }
</style>
