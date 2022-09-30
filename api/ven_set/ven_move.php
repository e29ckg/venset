<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$id     = $data->id;
$date_s = explode("T", $data->start);
$ven_date   = $date_s[0];

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

        $sql = "SELECT * FROM ven WHERE id = $id LIMIT 1 ";
        $query = $dbcon->prepare($sql);
        $query->execute();
        $res_v = $query->fetch(PDO::FETCH_OBJ);

        $user_id = $res_v->user_id;
        $DN      = $res_v->DN;

        $sql_VU = "SELECT * FROM ven WHERE user_id = $user_id AND ven_date = '$ven_date' AND status = 2 LIMIT 1";
        $query_VU = $dbcon->prepare($sql_VU);
        $query_VU->execute();
        $res_VU = $query_VU->fetch(PDO::FETCH_OBJ);

        if($res_VU){
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'วันนี้มีเวรอยู่แล้ว', 'respJSON' => $res_VU->user_id));
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


        $sql = "UPDATE ven SET ven_date =:ven_date WHERE id = :id";        
        $query = $dbcon->prepare($sql);
        $query->bindParam(':ven_date',$ven_date, PDO::PARAM_STR);
        $query->bindParam(':id',$id, PDO::PARAM_INT);
        $res = $query->execute();

        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'respJSON' => $res));
        exit;
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}