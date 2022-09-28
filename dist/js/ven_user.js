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


    isLoading: false,
  }
  },
  async mounted(){
    this.url_base = window.location.protocol + '//' + window.location.host;
    this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
    this.get_profiles()
    
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
  
  
  reset_search(){
    this.q = ''
  }      
}
}).mount('#venSet')
