//获取应用实例
const app = getApp()

var sliderWidth = 96; // 需要设置slider的宽度，用于计算中间位置

Page({
  data: {
    tabs: ["全部", "待付款", "待发货", "待收货"],
    activeIndex: 0,
    sliderOffset: 0,
    sliderLeft: 0,
    storeName: '墨刀小集市集市集市集市集市集市集市集市',
    orderStatus: '等待买家付款',
    orderNumber: '102390219302139',
    productName: '商品名称',
    productPrice: '233.00',
    productDesc: '商品描述商品描述商品描述商品描述商品描述',
    productNumber: '1',
    orderTotalPrice: '233.00'
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    // console.log("test onLoad");
    var that = this;
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          sliderLeft: (res.windowWidth / that.data.tabs.length - sliderWidth) / 2,
          sliderOffset: res.windowWidth / that.data.tabs.length * that.data.activeIndex
        });
      }
    });
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

  tabClick: function (e) {
    this.setData({
      sliderOffset: e.currentTarget.offsetLeft,
      activeIndex: e.currentTarget.id
    });
  }

})
