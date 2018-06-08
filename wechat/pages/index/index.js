//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    hasUserInfo: false,
    canIUse: wx.canIUse('button.open-type.getUserInfo'),
    productImg: '/images/fruit.png',
    productTitle: '水果 6月1日推荐 抢购组合尾矿库扩扩多扩多',
    groupData: '抢购日期：6月1日-6月3日',
    groupTips: '这个季节吃什么呢？你想要吃什么我们就有什么',
    groupSubTips: '今日抢购开始，精选3款',
    fingerImage: '/images/index_finger.png',
    floatIcon1: '/images/order.png',
    floatIcon2: '/images/my.png',
    loginscene:''
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  gopclogin: function (scene) {

    if(scene !=''){
      wx.navigateTo({
        url: '../pclogin/pclogin?scene='+scene
      })
    }

  },
  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    if(options && options.scene){
      this.data.loginscene = options.scene;
    }else{
      this.data.loginscene = '';
    }
    app.apiloginCallback = () => {
      this.gopclogin(this.data.loginscene);
    }
    // console.log("test onLoad");
    if (app.globalData.userInfo) {
      this.setData({
        userInfo: app.globalData.userInfo,
        hasUserInfo: true
      })
      this.gopclogin(this.data.loginscene);
    } else if (this.data.canIUse){
      // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
      // 所以此处加入 callback 以防止这种情况
      app.userInfoReadyCallback = res => {
        this.setData({
          wxuserInfo: res.userInfo,
          hasUserInfo: true
        })
      }
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          app.globalData.wxuserInfo = res.userInfo
          this.setData({
            wxuserInfo: res.userInfo,
            hasUserInfo: true
          })
        }
      })
    }
  },

  onReady: function() {
    // 生命周期函数--监听页面初次渲染完成
    // console.log("test onReady");
  },

  onShow: function() {
    // 生命周期函数--监听页面显示
    // console.log("test onShow");
  },

  onHide:function() {
    // 生命周期函数--监听页面隐藏
    // console.log("test onHide");
  },

  onUnload: function() {
    // 生命周期函数--监听页面卸载
    // console.log("test onUnload");
  },

  gotoProductDetail: function() {
    wx.navigateTo({
      url: '/pages/productdetail/productdetail'
    })
  },

  gotoPersonalCenter: function() {
    wx.navigateTo({
      url: '/pages/personalcenter/personalcenter'
    })
  },

  gotoOrderManager: function() {
    wx.navigateTo({
      url: '/pages/ordermanager/ordermanager'
    })
  },

  getUserInfo: function(e) {
    console.log(e)
    app.globalData.userInfo = e.detail.userInfo
    this.setData({
      userInfo: e.detail.userInfo,
      hasUserInfo: true
    })
  }
})
