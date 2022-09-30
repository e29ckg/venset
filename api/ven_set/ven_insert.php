<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
/**
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
 */
$datas = array();
    // The request is using the POST method
    
    try{
        $user_id     = $data->uid;
        $ven_date    = $data->ven_date;
        $ven_com_id  = $data->ven_com_id;

        $sql = "SELECT * FROM ven_com WHERE id =$ven_com_id LIMIT 1";
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':ven_com_id'  ,$ven_com_id,  PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if(!$result){
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'ไม่มีคำสั่งนี้', 'respJSON' => $result));
            exit;
        }

        $id = time();
        $ven_time    = $result->ven_time;
        $DN          = $result->DN;
        $ven_month   = $result->ven_month;
        $create_at   = date("Y-m-d H:i:s");
        
        $ref1 = generateRandomString($length = 20);
        $ref2 =  $result->ref;

        $sql_VU = "SELECT * FROM ven WHERE user_id = $user_id AND ven_date = '$ven_date' AND status = 2 LIMIT 1 ";
        $query_VU = $dbcon->prepare($sql_VU);
        $query_VU->execute();
        $res_VU = $query_VU->fetch(PDO::FETCH_OBJ);

        if($res_VU){
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'วันนี้มีเวรอยู่แล้ว', 'respJSON' => $res_VU));
            exit;
        }

        if($DN =='กลางคืน'){
            $ven_date_u1 = date("Y-m-d", strtotime('+1 day', strtotime($ven_date)));
            $sql = "SELECT * FROM ven WHERE user_id = $user_id AND ven_date = '$ven_date_u1' AND DN='กลางวัน' AND status = 2 LIMIT 1";
            $query = $dbcon->prepare($sql);
            $query->execute();
            $res = $query->fetch(PDO::FETCH_OBJ);

            if($res){
                http_response_code(200);
                echo json_encode(array('status' => false, 'message' => 'วันพรุ่งนี้('.$ven_date_u1.')มีกลางวัน', 'respJSON' => $res));
                exit;
            }
        }
        if($DN =='กลางวัน'){
            $ven_date_u1 = date("Y-m-d", strtotime('-1 day', strtotime($ven_date)));
            $sql = "SELECT * FROM ven WHERE user_id = $user_id AND ven_date = '$ven_date_u1' AND DN='กลางคืน' AND status = 2 LIMIT 1";
            $query = $dbcon->prepare($sql);
            $query->execute();
            $res = $query->fetch(PDO::FETCH_OBJ);

            if($res){
                http_response_code(200);
                echo json_encode(array('status' => false, 'message' => 'วันที่('.$ven_date_u1.')มีเวรกลางคืน', 'respJSON' => $res));
                exit;
            }
        }      

        $sql = "INSERT INTO ven(id, ven_date, ven_time, DN, ven_month, ven_com_id, user_id, status, ref1, ref2, create_at) 
                VALUE(:id, :ven_date, :ven_time, :DN, :ven_month, :ven_com_id, :user_id, 2, :ref1, :ref2, :create_at);";      

        $query = $dbcon->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_INT);
        $query->bindParam(':ven_date',$ven_date, PDO::PARAM_STR);
        $query->bindParam(':ven_time',$ven_time, PDO::PARAM_STR);
        $query->bindParam(':DN',$DN, PDO::PARAM_STR);
        $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);
        $query->bindParam(':ven_com_id',$ven_com_id, PDO::PARAM_INT);
        $query->bindParam(':user_id',$user_id, PDO::PARAM_STR);
        $query->bindParam(':ref1',$ref1, PDO::PARAM_STR);
        $query->bindParam(':ref2',$ref2, PDO::PARAM_STR);
        $query->bindParam(':create_at',$create_at, PDO::PARAM_STR);
        $query->execute();

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'สำเร็จ'));
            exit;
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}

function generateRandomString($length = 20) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}