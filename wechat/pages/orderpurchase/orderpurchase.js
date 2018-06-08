//获取应用实例
const app = getApp()

Page({
  data: {
    // input默认是1
    num: 1,
    // 使用data数据对象设置样式名
    minusStatus: 'disabled',
    buyerName: '',
    buyerPhonenumber: '',
    addressIcon: '/images/address.png',
    // buyerAddress: '广东省深圳市xxxxx',
    addressArrowRight: '/images/arrowright.png',
    productName: '山东红富士',
    productPrice: '￥5.2',
    productTotalPrice: '￥28.8',
    isExtraPay: '免运费',
    region: ['广东省', '深圳市', '龙岗区'],
    customItem: '全部',
    detailAddressValue: ''
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    // console.log("test onLoad");
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

  /* 点击减号 */
  bindMinus: function() {
    var num = this.data.num;
    // 如果大于1时，才可以减
    if (num > 1) {
        num --;
    }
    // 只有大于一件的时候，才能normal状态，否则disable状态
    var minusStatus = num <= 1 ? 'disabled' : 'normal';
    // 将数值与状态写回
    this.setData({
      num: num,
      minusStatus: minusStatus
    });
  },
  /* 点击加号 */
  bindPlus: function() {
    var num = this.data.num;
    // 不作过多考虑自增1
    num ++;
    // 只有大于一件的时候，才能normal状态，否则disable状态
    var minusStatus = num < 1 ? 'disabled' : 'normal';
    // 将数值与状态写回
    this.setData({
      num: num,
      minusStatus: minusStatus
    });
  },

  /* 输入框事件 */
  bindManual: function(e) {
    var num = e.detail.value;
    // 将数值与状态写回
    this.setData({
      num: num
    });
  },

  gotoPayment: function() {
    console.log('支付');
  },

  chooseAddress: function() {
    wx.navigateTo({
      url: '/pages/addressmanager/addressmanager'
    })
  },

  bindRegionChange: function (e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      region: e.detail.value
    })
  },

  inputReceiverName: function(e) {
    if (e && e.detail && e.detail.value) {
      this.setData({
        buyerName: e.detail.value
      })
    }
  },

  inputReceiverContact: function(e) {
    if (e && e.detail && e.detail.value) {
      this.setData({
        buyerPhonenumber: e.detail.value
      })
    }
  },

  inputDetailAddress: function(e) {
    // console.log(e.detail.value);
    if (e && e.detail && e.detail.value) {
      this.setData({
        detailAddressValue: e.detail.value
      })
    }
  }

})
