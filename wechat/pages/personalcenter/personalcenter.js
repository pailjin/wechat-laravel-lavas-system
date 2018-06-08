//获取应用实例
const app = getApp()

// 引入配置
var config = require('../../config');
var Util = require('../../utils/util.js');

Page({
  data: {
    usericon: '/images/fruit.png',
    nickname: '',
    nicknameInMP: 'nicknameInMP',
    usersex: '',
    usersexInMP: 'usersexInMP',
    useraddress: ''
  },

  onLoad: function(options) {
    // 生命周期函数--监听页面加载
    // console.log("test onLoad");
    var apipath = '/me'
    Util.apiCommonGet(apipath,(err,ret)=>{
      if(err){

      }else{

      }
    })
  },

  onReady: function() {
    // 生命周期函数--监听页面初次渲染完成
    // console.log("test onReady");
  },

  onShow: function() {
    // 生命周期函数--监听页面显示
    // console.log("test onShow");
    var newObj = wx.getStorageSync(Util.ITEM_RETURN_VALUE)
    console.log("newObj===", newObj);
    // return
    if(newObj && newObj.ifchange){
      if (newObj.tag === 'nicknameInMP') {
        if (newObj.value !== this.data.nickname) {
          this.setData({
            nickname: newObj.value
          })
        }
        this.modifyUserInfo({nickname:newObj.value});
      } else if (newObj.tag === 'usersexInMP') {
        if (newObj.value !== this.data.usersex) {
          this.setData({
            usersex: newObj.value
          })
        }
      }
    }


  },

  onHide:function() {
    // 生命周期函数--监听页面隐藏
    // console.log("test onHide");
  },

  onUnload: function() {
    // 生命周期函数--监听页面卸载
    // console.log("test onUnload");
  },
  modifyUserInfo: function(newdata) {
    var userid = '';
    if(app.globalData.userInfo){
      userid = app.globalData.userInfo.id;
    }
    var apipath = '/user/updatebyuid/'+userid
    Util.apiCommonPost(apipath,newdata,(err,ret)=>{
      if(err){

      }else{

      }
    })
  },
  modifyUserIcon: function() {
    var that = this;
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        console.log(res);
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        // var filePath = res.tempFilePaths[0]
        // qiniuUploader.upload(filePath, (res) => {
        //   // 每个文件上传成功后,处理相关的事情
        //   // 其中 info 是文件上传成功后，服务端返回的json，形式如
        //   // {
        //   //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
        //   //    "key": "gogopher.jpg"
        //   //  }
        //   // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
        //   var userid = 0;
        //   if(app.globalData.dbUserInfo){
        //     userid = app.globalData.dbUserInfo.id
        //   }else{
        //     return
        //   }
        //   Util.apiUpdateUserInfo(userid,{
        //     iconurl: res.imageURL
        //   },(err,ret)=>{
        //     if(err){
        //
        //     }else{
        //       that.setData({
        //         usericon: res.imageURL
        //       })
        //     }
        //   })
        // }, (error) => {
        //     console.log('error: ' + error);
        // }, {
        //   region: 'SCN',
        //   domain: config.service.qiniudomain, // // bucket 域名，下载资源时用到。如果设置，会在 success callback 的 res 参数加上可以直接使用的 ImageURL 字段。否则需要自己拼接
        //   //key: 'customFileName.jpg', //请用用户前缀+时间戳. [非必须]自定义文件 key。如果不设置，默认为使用微信小程序 API 的临时文件名
        //   // 以下方法三选一即可，优先级为：uptoken > uptokenURL > uptokenFunc
        //   //uptoken: '[yourTokenString]', // 由其他程序生成七牛 uptoken
        //   uptokenURL: config.service.requestUrl+'/uptoken', // 从指定 url 通过 HTTP GET 获取 uptoken，返回的格式必须是 json 且包含 uptoken 字段，例如： {"uptoken": "[yourTokenString]"}
        //   //uptokenFunc: function() {return '[yourTokenString]';}
        // });
      }
    })
  },

})
