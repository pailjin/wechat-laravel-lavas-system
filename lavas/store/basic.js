import axios from 'axios';

export const state = () => ({
    global_token:'',
    token: {
        newtoken: '无',
        oldtoken: '无'
    },
    logininfo: {
      name:'',
      password:'',
      response: {}
    },
    header: {show: true},
    global_userinfo: {
      'isLogin': false,
      'name':'',
      'id': 0
    },
    homeNavValue:'dashboard',
    global_loginqrcode : '',
    global_pcloginret : {},
    global_pclogin_scene:''
});

export const mutations = {
    SET_NAV_VALUE(state, navValue) {
      state.homeNavValue = navValue;
    },
    SET_GLOBAL_TOKEN(state, token) {
      state.global_token = token;
    },
    SET_LOGIN_INFO(state,logininfo){
      state.logininfo = logininfo;
    },
    SET_USER_INFO(state,userinfo){
      state.global_userinfo = userinfo;
    },
    SET_USER_LOGIN_QRCODE(state,qrcode){
      state.global_loginqrcode = qrcode;
    },
    SET_USER_LOGIN_QRCODE_RET(state,qrcoderet){
      state.global_pcloginret = qrcoderet;
    },
    SET_USER_LOGIN_QRCODE_SCENE(state,qrcodescene){
      state.global_pclogin_scene = qrcodescene;
    }
};
export const actions = {
    async setHomeNavValue({ commit, state },params){
      // console.log('params===',params);
      commit('SET_NAV_VALUE', params);
    },
    async PC_qrcode_login({commit}, params){//params == { token, page}
      let scene = params.scene;
      //API URL api.abc.com
      let url = 'https://api.abc.com/api/pccheckwxqrcode/'+scene;

      axios.get(url)
        .then(function (listresult) {
          if(listresult.data && listresult.data.result=='ok'){
            var ret = listresult.data.ret
            commit('SET_GLOBAL_TOKEN',ret.token)

            var newuserinfo=ret.userinfo;
            newuserinfo['isLogin'] = true;
            commit('SET_USER_INFO',newuserinfo)
          }else{
            if(listresult.data && listresult.data.ret){
              var ret = listresult.data.ret//errorcode是状态
              commit('SET_USER_LOGIN_QRCODE_RET',ret)
            }

            commit('SET_GLOBAL_TOKEN','')
          }
        })
        .catch(function (error) {
          console.log(error);
          commit('SET_GLOBAL_TOKEN','')
        });
    },
    async getUserloginqrcode({commit}, params){
      commit('SET_USER_LOGIN_QRCODE','')
      // let new_token = params.token;
      let url = 'https://api.abc.com/api/getwxqrcode';

      axios.get(url)
        .then(function (result) {
          if(result.data && result.data.result=='ok'){
            var qrcode = result.data.ret;
            var qrcodescene = result.data.scene;
            // console.log("qrcode==",qrcode);
            commit('SET_USER_LOGIN_QRCODE',qrcode)
            commit('SET_USER_LOGIN_QRCODE_SCENE',qrcodescene)
          }
          // var qrcode = result.data

          // commit('SET_USER_LOGIN_QRCODE',qrcode)
        })
        .catch(function (error) {
          console.log(error);
        });
    }


};
