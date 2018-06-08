//获取应用实例
const app = getApp()

Page({
  data: {
    imgUrls:[
      '/images/fruit.png',
      '/images/productdetail1.jpeg',
      '/images/productdetail2.jpeg'
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1300,
    bg: '#C79C77',
    Height: "",     //这是swiper要动态设置的高度属性
    // releaseFocus: false,
    productDetailTitle: '有机食物大集合有机食物大集合有机食物大集合有机食物大集合有机食物大集合',
    productDetailOverDesc: '距离抢购结束',
    productDetailOverTime: '48时21分46秒',
    productDetailFeeTips: '满69免运费，不满69加收6元配送费，小区内全部免费送货上门',
    productDetailRefundTips: '下单后，如果未成团时间结束后，系统安排退款',
    groupProductimage: '/images/fruit.png',
    groupProductTitle: '山西红富士苹果',
    groupProductSubTitle: '又脆又甜又香',
    groupProductOriginalPrice: '3.6',
    groupProductCommonPrice: '7.2',
    groupProductNumber: '100',
    groupProductSoldProgress: '20',
    consultTitle: '咨询/评价',
    productDetailConsultIcon: '/images/fruit.png',
    productDetailConsultName: '不告诉你我是谁',
    productDetailConsultData: '2018-06-19',
    productDetailConsultContent: '你这个苹果咋样啊斤斤计较军军军军军军军啦啦啦啦啦啦啦啦啦啦啦啦啦啊',
    productDetailAdminReply: '300块一斤',
    productDetailInputContent: '',
    productDetailBtnFlag: false
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

  toupper:function(){
    console.log("触发了toupper");
  },

  // imgHeight:function(e){
  //   console.log(e);
  //   var winWid = wx.getSystemInfoSync().windowWidth; //获取当前屏幕的宽度
  //   console.log('winWid:' + winWid);
  //   var imgh=e.detail.height;//图片高度
  //   console.log('imgh:' + imgh);
  //   var imgw=e.detail.width;//图片宽度
  //   console.log('imgw:' + imgw);
  //   var swiperH=winWid*imgh/imgw + "px"//等比设置swiper的高度。 即 屏幕宽度 / swiper高度 = 图片宽度 / 图片高度  ==》swiper高度 = 屏幕宽度 * 图片高度 / 图片宽度
  //   console.log('swiperH:' + swiperH);
  //   this.setData({
  //     Height:swiperH//设置高度
  //   })
  // },

  textareaInputMethods: function(e) {
    console.log(e);
    if (e.detail && e.detail.value) {
      this.setData({
        productDetailBtnFlag: true,
        productDetailInputContent: e.detail.value
      })
    } else {
      this.setData({
        productDetailBtnFlag: false,
        productDetailInputContent: ''
      })
    }
  },

  sendConsultMessageMethod: function() {
    console.log('发送评论');
    this.setData({
      productDetailInputContent: '',
      productDetailBtnFlag: false
    })
  },

  purchaseMethod: function() {
    console.log('购买');
    wx.navigateTo({
      url: '/pages/orderpurchase/orderpurchase'
    })
  }

})
