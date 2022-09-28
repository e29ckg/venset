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

    ven_com_id: '1566445991',
    ven_com_num: '1245/2565',
    ven_com_date: '1245/2565',
    ven_month : '2022-10',
    ven_time  : '08:30:02',
    DN        : 'กลางวัน',
    u_role    : 'ผู้พิพากษา',
    ven_com_name    : '',
    price    : '1500',

    isLoading: false,
  }
  },
  async mounted(){
    this.url_base = window.location.protocol + '//' + window.location.host;
    this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
    await this.get_vens()
    this.get_profiles()
    
  },
  watch: {
    q(){
      this.ch_search_pro()
    }
  },
  methods: {
    cal_render(){
      // var calendarEl = document.getElementById('calendar');
      var calendarEl = this.$refs['calendar'];
      
      var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'th',
          events: this.datas,
          // googleCalendarApiKey: 'AIzaSyDcnW6WejpTOCffshGDDb4neIrXVUA1EAE',
          // events: 'en.usa#holiday@group.v.calendar.google.com',
          eventClick: (info)=> {
              console.log(info.event.id +' '+info.event.title)
              console.log(info.event.extendedProps)
              // this.cal_click(info)
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

              if (!confirm("Are you sure about this change?")) {
                  info.revert();
              }else{
                this.event_drop(info.event.id,info.event.start)
              }
          },
          droppable: true,
          drop: (info)=> {
              // console.log(info.draggedEl.dataset.uid,info.dateStr)
              this.drop_insert(info.draggedEl.dataset.uid,info.dateStr)                            
              // info.revert();
              // is the "remove after drop" checkbox checked?
              // if (checkbox.checked) {
                  // if so, remove the element from the "Draggable Events" list
                  info.draggedEl.parentNode.removeChild(info.draggedEl);
              // }
              
          }
      });
      calendar.render(); 
  },
  drop_insert(uid,dateStr){
    axios.post(this.url_base_app + '/api/ven_set/ven_insert.php',{
                        uid         : uid,
                        ven_date    : dateStr,
                        ven_time    : this.ven_time,
                        DN          : this.DN,
                        ven_month   : this.ven_month,
                        ven_com_id  : this.ven_com_id
                      })
        .then(response => {
            console.log(response.data);
            if (response.data.status) {
              this.get_vens()
            } 
        })
        .catch(function (error) {        
            console.log(error);
            
        });
    
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
        } 
    })
    .catch(function (error) {
        console.log(error);
    });
  },
  get_profiles(){
      axios.get(this.url_base_app + './api/ven_set/get_users.php')
          .then(response => {
              console.log(response.data.respJSON);
              if (response.data.status) {
                  pfs = response.data.respJSON;
                  for (let i = 0; i < pfs.length; i++) {
                      this.profiles.push({
                          'id' : pfs[i].user_id,
                          'uid' : pfs[i].user_id,
                          'name' : pfs[i].name,
                          'sname' : pfs[i].sname,
                          
                      })
                  }
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
