//myClub.js

// 引入配置
var config = require('../../config');
var Util = require('../../utils/util.js');
//获取应用实例
const app = getApp()

Page({
  data: {
    // bannerImg: '/pages/images/myclub.jpg',
    // userIcon: '/pages/images/iconimg.png',
    scene:''
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    console.log("test onLoad",options);
    this.data.scene = options.scene;
    var scene = this.data.scene;
    Util.apiCommonGet(`/checkwxqrcode/${scene}`, (err, ret) => {
      if(err){
        wx.showToast({
          title: '失败',
          icon: 'none',
          duration: 2000
        })
        return;
      }

      // console.log('ret',ret);
      if(ret.result == 'ok'){

      }else{
        wx.showToast({
          title: ret.ret.error,
          icon: 'none',
          duration: 2000
        })
      }
    })

  },

  onReady: function() {
    // 生命周期函数--监听页面初次渲染完成
//     console.log("test onReady");
  },

  onShow: function() {
    // 生命周期函数--监听页面显示
  //   console.log("test onShow");

  },

  onHide:function() {
    // 生命周期函数--监听页面隐藏
//     console.log("test onHide");
  },

  onUnload: function() {
    // 生命周期函数--监听页面卸载
//     console.log("test onUnload");
  },

  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
//     console.log("test onPullDownRefresh");
  },

  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
//     console.log("test onReachBottom");
  },
  gologin:function(){
    if(this.data.scene==''){
      wx.navigateBack({
         delta: -1
      })
    }else{
      var scene = this.data.scene;
      Util.apiCommonGet(`/permitwxqrcode/${scene}`, (err, ret) => {
        if(err){
          wx.showToast({
            title: '失败',
            icon: 'none',
            duration: 2000
          })
          return;
        }

        console.log('ret',ret);
        if(ret.result == 'ok'){
          wx.showToast({
            title: '成功',
            icon: 'success',
            duration: 2000
          })
          wx.navigateBack({
             delta: -1
          })
        }else{
          wx.showToast({
            title: ret.ret.error,
            icon: 'none',
            duration: 2000
          })
        }
      })
    }
  },
  rejectlogin:function(){
    if(this.data.scene==''){
      wx.navigateBack({
         delta: -1
      })
    }else{
      var scene = this.data.scene;
      Util.apiCommonGet(`/rejectwxqrcode/${scene}`, (err, ret) => {
        if(err){
          wx.showToast({
            title: '失败',
            icon: 'none',
            duration: 2000
          })
          return;
        }

        console.log('ret',ret);
        if(ret.result == 'ok'){
          wx.showToast({
            title: '成功',
            icon: 'success',
            duration: 2000
          })
          wx.navigateBack({
             delta: -1
          })
        }else{
          wx.showToast({
            title: ret.ret.error,
            icon: 'none',
            duration: 2000
          })
        }
      })
    }
  },
  gohome:function(){
    // console.log("gohome");
    // wx.redirectTo({
    //   url: '../index/index'
    // })
    wx.navigateBack({
       delta: -1
    })
  },
  closewx:function(){
    wx.navigateBack({
       delta: -2
    })
  }

  // join:function () {
  //   wx.navigateTo({
  //     url: '../clubDetails/clubDetails',
  //   })
  // }

})
