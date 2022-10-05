Vue.createApp({
    data() {
        return {
            q:'',
            status:true,
            url_base:'',
            url_base_app:'',
            url_base_now:'',
            datas: '',
            ven_com : {
              ven_com_num   : '',
              ven_com_date  : '',
              ven_month     : '',
              ven_com_detail : [
                {
                  'ven_time'  : '',
                  'DN'        : '',
                  'u_role'    : '',
                  'ven_com_name'  : '',
                  'price'     : '',
                  'comment'   : '',
                  'st'        : '',
                }],
              act:'insert',
            },
            sel_ven_month :[],
            sel_u_role    :['ผู้พิพากษา','ผู้พิพากษา2','ผอ./แทน','ปชส.','งานหมาย','การเงิน','รับฟ้อง/หน้าบัลลังก์','หน้าบัลลังก์','จนท.'],
            sel_u_role_t  :{'':'00','ผู้พิพากษา':'00', 'ผู้พิพากษา2':'01', 'ผอ./แทน':'11','ปชส.':'12','งานหมาย':'13','การเงิน':'14','รับฟ้อง/หน้าบัลลังก์':'15','หน้าบัลลังก์':'16','จนท.':'17'},
            sel_DN        :['กลางวัน','กลางคืน'],
            sel_DN_t      :{'':'08:30','กลางวัน':'08:30','กลางคืน':'16:30'},
            sel_ven_name  :['เยาวชน,ขอปล่อย/แขวง/ค้น-จับ', 'เยาวชน,ขอปล่อย/แขวง', 'เยาวชน,ขอปล่อย/ค้น-จับ', 'เยาวชน,ขอปล่อย', 'ค้น-จับ'],
                                   
            isLoading: false,
        }
    },
    mounted(){
      this.url_base = window.location.protocol + '//' + window.location.host;
      this.url_base_app = window.location.protocol + '//' + window.location.host + '/venset/';
      this.get_ven_com()
      this.get_ven_month()
    },
    watch: {
      q(){
        this.ch_search_pro()
      }
    },
    methods: {   
      v_ch(index,u_role,DN){
        this.ven_com.ven_com_detail[index].ven_time = this.sel_DN_t[DN] + ':' +this.sel_u_role_t[u_role]
        // console.log(this.sel_DN_t[DN] + ':' +this.sel_u_role_t[u_role])
        // return this.sel_u_role
      },   
      get_ven_com(){
        axios.get(this.url_base_app + './api/ven_order/get_ven_coms.php')
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
      save_ven_com(){
        let l = this.ven_com.ven_com_detail.length - 1
        if (this.ven_com.ven_com_num && this.ven_com.ven_com_date && this.ven_com.ven_month
          && this.ven_com.ven_com_detail[l].u_role
          && this.ven_com.ven_com_detail[l].DN
          && this.ven_com.ven_com_detail[l].ven_com_name
          && this.ven_com.ven_com_detail[l].price
          ) {
          axios.post(this.url_base_app + './api/ven_order/action.php',{ven_com:this.ven_com})
            .then(response => {
                console.log(response.data);
                if (response.data.status) {
                    this.datas = response.data.respJSON;      
                    this.get_ven_com()  
                    this.$refs['close_modal'].click()  
                    this.ven_com = {
                      ven_com_num   : '',
                      ven_com_date  : '',
                      ven_month     : '',
                      ven_com_detail : [
                        {
                          'ven_time'  : '',
                          'DN'        : '',
                          'u_role'    : '',
                          'ven_com_name'  : '',
                          'price'     : '',
                          'comment'   : '',
                          'st'        : '',
                        }],
                      act:'insert',
                    }
                    let icon    = 'success'
                    let message = response.data.message
                    this.alert(icon,message,1000)
  
                } 
            })
            .catch(function (error) {
                console.log(error);
            });
        }else{
          let icon    = 'warning'
          let message = []
          
          if(this.ven_com.ven_com_num   == ''){message.push('กรุณป้อนคำสั่ง')}
          if(this.ven_com.ven_com_date  == ''){message.push('คำสังลงวันที่')}
          if(this.ven_com.ven_month     == ''){message.push('อยู่เวรเดือน')}
          
          if(this.ven_com.ven_com_detail[l].u_role   == ''){message.push('ผู้อยู่')}
          if(this.ven_com.ven_com_detail[l].DN       == ''){message.push('กลางวัน-กลางคืน')}
          if(this.ven_com.ven_com_detail[l].ven_com_name == ''){message.push('ชื่อเวร')}
          if(this.ven_com.ven_com_detail[l].price    == ''){message.push('ค่าเวร')}
          
          this.alert(icon,message)

        }

      },
      alert(icon,message,timer=0){
        swal.fire({
        icon: icon,
        title: message,
        showConfirmButton: true,
        timer: timer
      });
    },
      reset_ven_com(){
        this.ven_com = {
          ven_com_num   : '',
          ven_com_date  : '',
          ven_month     : '',
          ven_com_detail : [
            {
              'ven_time'  : '',
              'DN'        : '',
              'u_role'    : '',
              'ven_com_name'  : '',
              'price'     : '',
              'comment'   : '',
              'st'        : '',
            }],
          act:'insert',
        }
      },
      update(ven_com_num,ven_com_date,ven_month){
        axios.post(this.url_base_app + './api/ven_order/get_ven_com_update.php',{
          ven_com_num   : ven_com_num,
          ven_com_date  : ven_com_date,
          ven_month     : ven_month})
        .then(response => {
            console.log(response.data.respJSON.id);
            if (response.data.status) {
                this.ven_com =  {
                  ven_com_num   : response.data.respJSON.ven_com_num,
                  ven_com_date  : response.data.respJSON.ven_com_date,
                  ven_month     : response.data.respJSON.ven_month,
                  ven_com_detail : response.data.respJSON.detail
                }  
                this.ven_com.act = 'update'
                this.$refs['show_modal'].click()                  
            } 
        })
      },                
      delete_com_ven(id){
        console.log(id)
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
            axios.post(this.url_base_app + './api/ven_order/ven_com_del.php',{id:id,action:'delete'})
            .then(response => {
                // console.log(response.data.respJSON.id);
                if (response.data.status) {
                  this.get_ven_com()           
                } 
            })

            Swal.fire({
                title: 'Deleted',
                text: "Your has been deleted.",
                icon: 'success',
                timer: 1000
              }
            )
          }
        })

        
      },   
      set_st(id){
        axios.post(this.url_base_app + './api/ven_order/ven_com_set_st.php',{id:id})
        .then(response => {
            // console.log(response.data.respJSON.id);
            if (response.data.status) {
              this.get_ven_com()           
            }
          }) 
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

    get_ven_month(){
      let   m = new Date();
      for (let i = 0; i < 5; i++) {  
        const d = new Date(2022,m.getMonth()+i);
        this.sel_ven_month.push({'ven_month':this.convertToYearMonthNum(d),'name': this.convertToDateThai(d)})
      }
    },
    convertToYearMonthNum(date) {
      var months_num = ["","01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
      return result   = date.getFullYear() + "-" + (months_num[( date.getMonth()+1 )]);
    },
    convertToDateThai(date) {
      var month_th = ["","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
      return result = month_th[( date.getMonth()+1 )]+" "+( date.getFullYear()+543 );
    },
    ven_p(){
      let l = this.ven_com.ven_com_detail.length - 1
      if(this.ven_com.ven_com_detail[l].u_role
        && this.ven_com.ven_com_detail[l].DN
        && this.ven_com.ven_com_detail[l].ven_com_name
        && this.ven_com.ven_com_detail[l].price ){
          this.ven_com.ven_com_detail.push({
              'ven_time'  : '',
              'DN'        : '',
              'u_role'    : '',
              'ven_com_name'  : '',
              'price'     : '',
              'comment'   : '',
              'st'        : '',
            })
        }else{
          let icon    = 'warning'
          let message = []
          if(this.ven_com.ven_com_detail[l].u_role   == ''){message.push('ผู้อยู่')}
          if(this.ven_com.ven_com_detail[l].DN       == ''){message.push('กลางวัน-กลางคืน')}
          if(this.ven_com.ven_com_detail[l].ven_com_name == ''){message.push('ชื่อเวร')}
          if(this.ven_com.ven_com_detail[l].price    == ''){message.push('ค่าเวร')}          
          this.alert(icon,message)
        }
    },
    ven_p_d(index){
      console.log(index)
      this.ven_com.ven_com_detail.splice(index,1)
    },
    reset_search(){
      this.q = ''
    }      
  }
}).mount('#venOrder')
  
  