import axios from 'axios';

export const state = () => ({
  global_userlist:{}
});

export const mutations = {
  SET_NEW_USER_LIST(state,userlist){
    var saveData = []
    if(state.global_userlist.data){
      saveData = state.global_userlist.data
    }
    var tmpuserlist = userlist;
    if(saveData.length==0){
      tmpuserlist.data = userlist.data
    }else{
      tmpuserlist.data = saveData.concat(userlist.data)
    }

    state.global_userlist = tmpuserlist;
  },
  CLEAN_NEW_USER_LIST(state,userlist){
    state.global_userlist = {};
  }

};
export const actions = {
    async getUserlist({commit}, params){//params == { token, page}
      if(params.page<=1){
        commit('CLEAN_NEW_USER_LIST',{})
      }
      let new_token = params.token;
      let url = 'https://api.abc.com/api/auth/getuserlistpage?token='+new_token+'&page='+params.page;

      axios.get(url)
        .then(function (listresult) {
          console.log("listresult===",listresult);
          if(listresult.data && listresult.data.status=='ok'){
            var newuserlist = listresult.data.ret
            commit('SET_NEW_USER_LIST',newuserlist)
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
};
