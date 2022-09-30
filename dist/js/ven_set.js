// const { info } = require("console");


Vue.createApp({
  data() {
    return {
      q:'',
      url_base:'',
      url_base_app:'',
      url_base_now:'',
      datas: [
        {
            id: 'a',
            title: 'my event',
            start: '2022-09-01',
            extendedProps: {
                uid: 5555,
                uname: '',
                ven_date: '',
                ven_time: '',
                DN: '',
                ven_month: '',
                ven_com_id: '',
                st: '',

            }
        }
    ],
    data_event:{ 
        uid: 5555,
        uname: '',
        ven_date: '',
        ven_time: '',
        DN: '',
        ven_month: '',
        ven_com_id: '',
        st: '',
    },
    profiles:[],

    ven_coms  :[],
    ven_coms_index:'',

    ven_com_id: '',
    ven_month : '',
    DN        : '',
    u_role    : '',
    price     : '',

    label_message : '<--กรุณาเลือกคำสั่ง',
    isLoading : false,
  }
  },
  async mounted(){
    this.url_base = window.location.protocol + '//' + window.location.host;
    this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
    // const d = 
    this.ven_month = new Date();
    await this.get_vens()
    await this.get_ven_coms()
    // this.get_profiles()
    
  },
  watch: {
    q(){
      this.ch_search_pro()
    },
    ven_coms_index(){
      let i = this.ven_coms_index
      if(this.ven_coms[i].id){
        this.label_message = 
        // this.ven_coms[i].id + ' -> ' 
        this.ven_coms[i].u_role + ' -> ' 
        + this.ven_coms[i].DN + ' -> ' 
        + this.ven_coms[i].ven_month + ' -> ' 
        + this.ven_coms[i].ven_com_name + ' -> ' 
        + this.ven_coms[i].price ;

        this.ven_com_id =this.ven_coms[i].id 
        this.ven_month  =this.ven_coms[i].ven_month
        this.u_role     =this.ven_coms[i].u_role
        this.DN         =this.ven_coms[i].DN
        
        this.cal_render()
      }
    }
  },
  methods: {
    cal_render(){
      // var calendarEl = document.getElementById('calendar');
      var calendarEl = this.$refs['calendar'];
      
      var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          initialDate: this.ven_month,
          firstDay    : 1,
          locale      : 'th',
          events      : this.datas,
          eventClick: (info)=> {
              console.log(info.event.id +' '+info.event.title)
              console.log(info.event.extendedProps)
              this.cal_click(info.event.id)
          },
          dateClick:(date)=>{
              console.log(date)
              // this.cal_modal.date_meet = date.dateStr
              this.$refs['modal-show'].click();
          },
          editable: true,
          eventDrop: (info)=> {
              console.log(info.event)

              // alert(info.event.id + info.event.title + " was dropped on " + info.event.start);
              // alert(info.event.title + " was dropped on " + info.event.start.toISOString());

              // if (!confirm("Are you sure about this change?")) {
              //     info.revert();
              // }else{
                if(!this.event_drop(info.event.id,info.event.start)){
                  info.revert();
                }
              // }
          },
          droppable: true,
          drop: (info)=> {
              // console.log(info.draggedEl.dataset.uid,info.dateStr)
              this.drop_insert(info.draggedEl.dataset.uid,info.dateStr)                            
              // info.revert();
              // is the "remove after drop" checkbox checked?
              // if (checkbox.checked) {
                  // if so, remove the element from the "Draggable Events" list
                  // info.draggedEl.parentNode.removeChild(info.draggedEl);
              // }
              
          }
      });
      calendar.render(); 
  },
  
  cal_click(id){
    axios.post(this.url_base_app + '/api/ven_set/get_ven.php',{id:id})
        .then(response => {
          console.log(response.data);
          if (response.data.status) {
            this.data_event = response.data.respJSON[0]
            this.$refs['show_modal'].click()

          } else{
            let icon    = 'warning'
            let message = response.data.message                
            this.alert(icon,message,0)

          }
      })
      .catch(function (error) {        
      console.log(error);

    });

  },
  drop_insert(uid,dateStr){
    if(this.ven_com_id){
      axios.post(this.url_base_app + '/api/ven_set/ven_insert.php',{
                          uid         : uid,
                          ven_date    : dateStr,
                          ven_com_id  : this.ven_com_id
                        })
          .then(response => {
              console.log(response.data);
              if (response.data.status) {
                this.get_vens()
                swal.fire({
                  icon: 'success',
                  title: response.data.message,
                  showConfirmButton: true,
                  timer: 1000
                });
              } else{
                let icon    = 'warning'
                let message = response.data.message                
                this.alert(icon,message,0)
                this.get_vens()
                
              }
            })
            .catch(function (error) {        
              console.log(error);
              
            });
    }else{
      let icon    = 'warning'
      let message = []
      if(this.ven_com_id==''){message.push('กรุณาเลือกคำสั่ง')}            
      this.alert(icon,message,0)
      this.get_vens()
    }
    
  }, 
  event_drop(id,start){
    axios.post(this.url_base_app + './api/ven_set/ven_move.php',{id:id,start:start})
    .then(response => {
        console.log(response.data.respJSON);
        if (response.data.status) {
            this.datas = response.data.respJSON;
            this.get_vens()
            swal.fire({
              icon: 'success',
              title: response.data.message,
              showConfirmButton: true,
              timer: 1000
            });
            return true
        } else{
          icon = 'warning'
          message = response.data.message;
          this.alert(icon,message,timer=0)
          return false
        }
    })
    .catch(function (error) {
        console.log(error);
    });
  },
  get_vens(){
    axios.get(this.url_base_app + './api/ven_set/get_vens.php')
    .then(response => {
        console.log(response.data.respJSON);
        if (response.data.status) {
            this.datas = response.data.respJSON;
            this.cal_render()
            this.$refs['calendar'].focus()
        } 
    })
    .catch(function (error) {
        console.log(error);
    });
  },
  get_ven_coms(){
    axios.get(this.url_base_app + './api/ven_set/get_ven_coms.php')
    .then(response => {
        console.log(response.data.respJSON);
        if (response.data.status) {
            this.ven_coms = response.data.respJSON;
        } 
    })
    .catch(function (error) {
        console.log(error);
    });
  },
  sel_vem_com(ven_com_index){
    let vci = this.ven_coms[ven_com_index].id
    console.log(vci)
    // this.get_profiles()
    axios.post(this.url_base_app + './api/ven_set/get_users.php',{ven_com_id:vci})
          .then(response => {
              console.log(response.data.respJSON);
              if (response.data.status) {
                  // pfs = response.data.respJSON;
                  this.profiles = response.data.respJSON;
                  
              } 
          })
          .catch(function (error) {
              console.log(error);
          });
  },
  get_profiles(){
      axios.post(this.url_base_app + './api/ven_set/get_users.php',{ven_com_id:this.ven_com_id})
          .then(response => {
              console.log(response.data.respJSON);
              if (response.data.status) {
                  // pfs = response.data.respJSON;
                  this.profiles = response.data.respJSON;
                  // for (let i = 0; i < pfs.length; i++) {
                  //     this.profiles.push({
                  //         'id' : pfs[i].user_id,
                  //         'uid' : pfs[i].user_id,
                  //         'name' : pfs[i].name,
                  //         'sname' : pfs[i].sname,
                          
                  //     })
                  // }
              } 
          })
          .catch(function (error) {
              console.log(error);
          });
  },
  set_ven_com(){
    let i           = this.ven_coms_index
    this.ven_com_id = this.ven_coms[i].id
    this.ven_month  = this.ven_coms[i].ven_month
    this.DN         = this.ven_coms[i].DN
    this.u_role     = this.ven_coms[i].u_role
    this.ven_com_name = this.ven_coms[i].ven_com_name
    this.price      = this.ven_coms[i].price
  },
  ven_del(id){
    Swal.fire({
      title: 'Are you sure?',
      text  : "You won't be able to revert this!",
      icon  : 'warning',
      showCancelButton  : true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor : '#d33',
      confirmButtonText : 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        axios.post(this.url_base_app + './api/ven_set/ven_del.php',{id:id})
          .then(response => {
              console.log(response.data.respJSON);
              if (response.data.status) {
                icon = "success";
                message = response.data.message;
                this.alert(icon,message,1000)
                this.$refs['close_modal'].click()
                this.get_vens()
              }else{
                icon = "warning";
                message = response.data.message;
                this.alert(icon,message)
              } 
          })
          .catch(function (error) {
              console.log(error);
          });
      }
    })
    
  },   

  alert(icon,message,timer=0){
    swal.fire({
    icon: icon,
    title: message,
    showConfirmButton: true,
    timer: timer
  });
  },
  
  reset_search(){
    this.q = ''
  }      
}
}).mount('#venSet')
