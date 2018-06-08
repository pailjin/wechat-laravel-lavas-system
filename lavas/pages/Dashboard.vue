<template>

  <mu-appbar title="统计">
    <mu-icon-button icon="arrow_back" slot="left" to="/" replace/>
    <mu-flat-button label="expand_more" slot="right" @click.native="$router.go(-1)"/>
    <mu-icon-button icon="expand_more" slot="right"/>
  </mu-appbar>

  <!-- <div class="demo-raised-button-container">
    <mu-raised-button class="demo-raised-button" label="选择文件">
      <input type="file" class="file-button">
    </mu-raised-button>
    <mu-raised-button class="demo-raised-button" label="文字在后面" icon="android" primary/>
    <mu-raised-button class="demo-raised-button" label="文字在前面" labelPosition="before" icon="folder" secondary/>
    <mu-raised-button class="demo-raised-button" href="https://github.com/museui/muse-ui" label="链接按钮" labelPosition="after" secondary>
        <i class="mudocs-icon-custom-github mu-icon" style="font-size:24px;"></i>
    </mu-raised-button>
    <mu-raised-button class="demo-raised-button" icon="android" backgroundColor="#a4c639" />
  </div> -->
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
        bottomNav: 'recents'
      }
    },
    async asyncData({store, route}) {
        setState(store);

    },
    methods: {
      handleChange (val) {
        this.bottomNav = val
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
</style>
