<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

// $data = json_decode(file_get_contents("php://input"));


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$datas = array();


    // The request is using the POST method
    try{
        $sql = "SELECT * FROM ven_com
        WHERE status = 1 
        ORDER BY ven_month DESC, DN DESC, ven_time ASC
        LIMIT 50";
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        // if($query->rowCount() > 0){                        //count($result)  for odbc
        //     foreach($result as $rs){
        //         array_push($datas,array(
        //             'id'    => $rs->id,
        //             'title' => $rs->name,
        //             'start' => $rs->ven_date.' '.$rs->ven_time,

        //         ));
        //     }
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $result));
            exit;
        // }
     
        // http_response_code(200);
        // echo json_encode(array('status' => true, 'massege' => 'ไม่พบข้อมูล ', 'respJSON' => $datas));
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}