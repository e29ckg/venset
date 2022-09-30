Vue.createApp({
  data() {
    return {
      q:'',
      url_base:'',
      url_base_app:'',
      url_base_now:'',
      datas: '',

      
      user_id :'',
      order   :'',
      DN      :'',
      price   :'',
      comment :'',

    profiles:[],

    just:[],
    po:[],
    jnt_D:[],
    jnt_N:[],

    modal:'',


    isLoading: false,
  }
  },
  async mounted(){
    this.url_base = window.location.protocol + '//' + window.location.host;
    this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
    this.get_profiles()
    this.get_just()
  },
  watch: {
    q(){
      this.ch_search_pro()
    }
  },
  methods: {
  
    get_profiles(){
        axios.get(this.url_base_app + './api/ven_user/get_users_all.php')
            .then(response => {
                console.log(response.data.respJSON);
                if (response.data.status) {
                    this.datas = response.data.respJSON;
                    
                } 
            })
            .catch(function (error) {
                console.log(error);
            });
    },                
    get_just(){
        axios.post(this.url_base_app + './api/ven_user/get_users.php',{u_role:'ผู้พิพากษา',DN:'all'})
            .then(response => {
                console.log(response.data.respJSON);
                if (response.data.status) {
                    this.just = response.data.respJSON;
                    
                } 
            })
            .catch(function (error) {
                console.log(error);
            });
    },
    show_add_just(){
      
      this.$refs.show_modal.click()
    },                
    reset_modal(){},
  
    show_add_user(u_role,DN){
      axios.post(this.url_base_app + './api/ven_user/show_add_user.php',{u_role,DN})
            .then(response => {
                console.log(response.data.respJSON);
                if (response.data.status) {
                    this.datas = response.data.respJSON;
                    
                } 
            })
            .catch(function (error) {
                console.log(error);
            });
      this.$refs.show_modal.click()
    }, 
  reset_search(){
    this.q = ''
  }      
}
}).mount('#venSet')
