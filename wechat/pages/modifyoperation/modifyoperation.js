// 引入配置
var config = require('../../config');
var Util = require('../../utils/util.js');
//获取应用实例
const app = getApp()

Page({
  data: {
    inputValue: '',
    fromKeyword: '',
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    console.log("test onLoad",options);
    if (options && options.from) {
      console.log("test onLoad--options.query.from----",options.from);
      this.setData({
        fromKeyword: options.from,
        inputValue: options.orgvalue
      })
    }
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
    // wx.clearStorageSync(Util.ITEM_RETURN_VALUE, {})
  },

  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
//     console.log("test onPullDownRefresh");
  },

  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
//     console.log("test onReachBottom");
  },

  bindReplaceInput: function(event) {
    // console.log(event.detail)
    //if (this.data.fromKeyword === 'listNickname') {
    wx.setStorageSync(Util.ITEM_RETURN_VALUE, {
      value: event.detail.value,
      ifchange: 1,
      tag: this.data.fromKeyword
    })
    //}
  }
})
