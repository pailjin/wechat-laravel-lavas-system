//app.js
var config = require('./config');
App({
  onLaunch: function () {
    // 展示本地存储能力
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    wx.setStorageSync('logs', logs)
    var _this = this;
    // 登录
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        wx.request({
          url: config.service.requestUrl+'/wxlogin/'+res.code, //仅为示例，并非真实的接口地址
          data: {
          },
          header: {
            'content-type': 'application/json' // 默认值
          },
          method: 'GET',
          success: function(res) {
            console.log('wx.login---res==',res.data);

            if(res.data.status=='ok'){
              var ret = res.data.ret;
              _this.globalData.iflogin = true;
              _this.globalData.apitoken = ret.token;
              _this.globalData.userInfo = ret.userinfo;
              if (this.apiloginCallback) {
                this.apiloginCallback()
              }
            }else{
              _this.globalData.iflogin = false;
              _this.globalData.apitoken = '';
              _this.globalData.userInfo = null;
            }
          }
        })

      }
    })
    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              _this.globalData.wxuserInfo = res.userInfo

              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
      }
    })
  },
  globalData: {
    userInfo: null,
    wxuserInfo:null,
    apitoken: '',
    iflogin:false
  }
})
