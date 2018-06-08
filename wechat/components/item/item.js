Component({
  options: {
    multipleSlots: true // 在组件定义时的选项中启用多slot支持
  },

  properties: {
    // 这里定义了innerText属性，属性值可以在组件使用时指定
    itemLeft: {
      type: String,
      value: ''
    },

    itemRight: {
      type: String,
      value: '',
    },

    itemType: {
      type: String,
      value: ''
    },

    tag: {
      type: String,
      value: ''
    },
  },

  data: {

  },

  methods:{
    setInput: function() {
      wx.navigateTo({
        url: '/pages/modifyoperation/modifyoperation?from='+this.properties.tag+'&orgvalue=' + this.properties.itemRight
      })
    },

    setRadioChoose: function() {
      wx.navigateTo({
        url: '/pages/modifyradiooperation/modifyradiooperation?from='+this.properties.tag+'&orgvalue=' + this.properties.itemRight
      })
    }
  }
})
