Component({
  options: {
    multipleSlots: true // 在组件定义时的选项中启用多slot支持
  },

  properties: {
    // 这里定义了innerText属性，属性值可以在组件使用时指定
    advIcon: {
      type: String,
      value: ''
    },

    advTitle: {
      type: String,
      value: '',
    }
  },

  data: {

  },

  methods:{

  }
})
