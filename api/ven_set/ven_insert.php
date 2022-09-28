<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$id = time();
$user_id     = $data->uid;
$ven_date    = $data->ven_date;
$ven_time    = $data->ven_time;
$DN    = $data->DN;
$ven_month    = $data->ven_month;
$ven_com_id    = $data->ven_com_id;


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
        $sql = "INSERT INTO ven(id, ven_date, ven_time, DN, ven_month, ven_com_id, user_id, status) 
                                  VALUE(:id, :ven_date, :ven_time, :DN, :ven_month, :ven_com_id, :user_id, 1);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':id',$id, PDO::PARAM_INT);
                $query->bindParam(':ven_date',$ven_date, PDO::PARAM_STR);
                $query->bindParam(':ven_time',$ven_time, PDO::PARAM_STR);
                $query->bindParam(':DN',$DN, PDO::PARAM_STR);
                $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);
                $query->bindParam(':ven_com_id',$ven_com_id, PDO::PARAM_INT);
                $query->bindParam(':user_id',$user_id, PDO::PARAM_STR);
                $query->execute();

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'respJSON' => $data));
            exit;
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}