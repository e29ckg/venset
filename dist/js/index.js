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
      isLoading: false,
    }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
      this.cal_render()
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
                console.log(info.event)
                console.log(info.event.extendedProps)
                // this.cal_click(info)
            },
            dateClick:(date)=>{
                console.log(date)
                // this.cal_modal.date_meet = date.dateStr
                this.$refs['modal-show'].click();
            },
            editable: true,
            eventDrop: function(info) {
                alert(info.event.title + " was dropped on " + info.event.start);
                // alert(info.event.title + " was dropped on " + info.event.start.toISOString());

                if (!confirm("Are you sure about this change?")) {
                    info.revert();
                }
            },
            droppable: true,
            drop: (info)=> {
                this.drop_insert(info)                            
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
    get_profiles(){
        axios.get(this.url_base_app + './api/index/all_profile.php')
            .then(response => {
                console.log(response.data.respJSON);
                if (response.data.status) {
                    pfs = response.data.respJSON;
                    for (let i = 0; i < pfs.length; i++) {
                        this.profiles.push({
                            'uid' : pfs[i].user_id,
                            'name' : pfs[i].name
                        })
                    }
                } 
            })
            .catch(function (error) {
                console.log(error);
            });
    },                
    drop_insert(info){
        console.log('drop')
        console.log(info)
        console.log(info.draggedEl.dataset.event)
        axios.post(this.url_base_app + '/api/index/cal_insert.php',{data:info.draggedEl.dataset.event})
            .then(response => {
                console.log(response.data);
                if (response.data.status) {
                    this.cal_modal = response.data.responseJSON
                } 
            })
            .catch(function (error) {        
                console.log(error);
                
            });
        
    }, 
    ch_search_pro(){
      console.log(this.q)
      if(this.q.length > 0){
        this.isLoading = true;
        axios.post(this.url_base + '/estock/api/products/product_search.php',{q:this.q})
          .then(response => {
              if (response.data.status){
                this.datas = response.data.respJSON;                    
              }
          })
          .catch(function (error) {
              console.log(error);
          })
          .finally(() => {
            this.isLoading = false;
          })
      }else{
        this.get_products()
      }
    },
    reset_search(){
      this.q = ''
    }      
  }
}).mount('#appCal')
  
  