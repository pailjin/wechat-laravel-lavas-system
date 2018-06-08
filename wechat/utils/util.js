const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()

  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}


const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}

const ITEM_RETURN_VALUE ='itemReturnValue'

var config = require('../config');
const app = getApp()


function apiCommonPost(apipath, newdata,rcb){
  var curapitoken=app.globalData.apitoken;
  wx.request({
    url: config.service.requestUrl+apipath+'?token='+curapitoken,
    data: newdata,
    method:"POST",
    header: {
        'content-type': 'application/json' // 默认值
    },
    success: (res)=> {
      console.log("success===",res.data);
      if(res.data.status=='ko'){
        wx.showToast({
          title: '更新失败',
          icon: 'none',
          duration: 2000
        })
        rcb(res.data,null)
      }else{
        rcb(null,res.data)
      }
    },
    fail: (res)=> {
      console.log("fail==",res.data);

      wx.showToast({
        title: '网络异常,请刷新',
        icon: 'none',
        duration: 2000
      })
      rcb(res.data,null)
    }
  })
}

function apiCommonGet(apipath,rcb){// apipath example : /auth/me
  var curapitoken=app.globalData.apitoken;
  wx.request({
    url: config.service.requestUrl+apipath+'?token='+curapitoken,
    data: {},
    method:"GET",
    header: {
        'content-type': 'application/json' // 默认值
    },
    success: (res)=> {
      console.log("success===",res.data);
      if(res.data.error){
        wx.showToast({
          title: '从服务器获取失败',
          icon: 'none',
          duration: 2000
        })
        rcb(res.data.error,null)
      }else{
        rcb(null,res.data)
      }
    },
    fail: (res)=> {
      console.log("fail==",res.data);

      wx.showToast({
        title: '网络异常,请刷新',
        icon: 'none',
        duration: 2000
      })
      rcb(res.data,null)
    }
  })
}


module.exports = {
  formatTime: formatTime,apiCommonPost,apiCommonGet,
  ITEM_RETURN_VALUE: ITEM_RETURN_VALUE,
}
