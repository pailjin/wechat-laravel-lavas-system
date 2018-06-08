//modifyPersonalInfo.js

// 引入配置
var config = require('../../config');
var Util = require('../../utils/util.js');
//获取应用实例
const app = getApp()

Page({
  data: {
    radioItems: [
      { name: '男', value: '男' },
      { name: '女', value: '女' },
      { name: '保密', value: '保密'}
    ],
    fromKeyword: '',
    optionItems: ''
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    console.log("test onLoad",options);
    if (options && options.from) {
      console.log("test onLoad--options.query.from----",options.from);

      // var sexs = [
      //   { name: '女', value: '女' },
      //   { name: '男', value: '男' },
      //   { name: '保密', value: '保密' }
      // ]
      var sexs = this.data.radioItems
      for (var i = 0; i < sexs.length; i++) {
        if (sexs[i].value === options.orgvalue) {
          sexs[i].checked = true
          // sexs[i].style.display ='block'
        }else{
          sexs[i].checked = false
        }
      }

      console.log(sexs);

      this.setData({
        fromKeyword: options.from,
        optionItems: sexs,
        radioItems: sexs
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
  },

  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
//     console.log("test onPullDownRefresh");
  },

  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
//     console.log("test onReachBottom");
  },

  radioChange: function (e) {
    console.log('radio发生change事件，携带value值为：', e.detail.value);

    var radioItems = this.data.radioItems;
    for (var i = 0, len = radioItems.length; i < len; ++i) {
        radioItems[i].checked = radioItems[i].value == e.detail.value;
    }

    console.log(radioItems);
    this.setData({
        radioItems: radioItems
    });

    wx.setStorageSync(Util.ITEM_RETURN_VALUE, {
      value: e.detail.value,
      ifchange: 1,
      tag: this.data.fromKeyword
    })
  },

  // radioChange: function(e) {
  //   console.log('radio发生change事件，携带value值为：', e.detail.value)
  //   if (this.data.inputValue === e.detail.value) {
  //     for (var i = 0; i < this.data.optionItems.length; i++) {
  //       console.log(this.data.optionItems[i].value);
  //       if (this.data.optionItems[i].value === e.detail.value) {
  //         this.data.optionItems[i].checked = 'true'
  //       } else {
  //         this.data.optionItems[i].checked = 'false'
  //       }
  //     }
  //   }
  //
  //   wx.setStorageSync(Util.ITEM_RETURN_VALUE, {
  //     value: e.detail.value,
  //     ifchange: 1,
  //     tag: this.data.fromKeyword
  //   })
  // }

})
