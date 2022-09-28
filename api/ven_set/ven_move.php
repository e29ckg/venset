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