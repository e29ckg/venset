<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");
include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$data = $data->data;
// http_response_code(200);
// echo json_encode(array('status' => true, 'massege' => 'มีการทำงาน ','responseJSON' => $data->kkey));
// exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The request is using the POST method
    try{
            
        // $sql = "INSERT INTO main(kkey, mainkey, jote, jumley, kcase, date_start) VALUE(:kkey, :mainkey, :jote, :jumley, :kcase, :date_start)";
        
        // $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        // $query->bindParam(':mainkey',$data->mainkey, PDO::PARAM_STR);
        // $query->bindParam(':jote',$data->jote, PDO::PARAM_STR);
        // $query->bindParam(':jumley',$data->jumley1, PDO::PARAM_STR);
        // $query->bindParam(':kcase',$data->kcase, PDO::PARAM_STR);
        // $query->bindParam(':date_start',$data->date_start, PDO::PARAM_STR);

        // $query->execute();
        // if($query->rowCount() > 0){
            // echo "เพิ่มข้อมูลเรียบร้อย ok";
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'บันทึกเรียบร้อย', 'responseJSON' => $data));
        // }else{
        //     // echo "มีบางอย่างผิดพลาด";
        //     http_response_code(200);
        //     echo json_encode(array('status' => false, 'massege' => 'มีบางอย่างผิดพลาด', 'responseJSON' => $data));
        // }
     
        // http_response_code(200);
        // echo json_encode(array('status' => true, 'massege' => 'มีการทำงาน ' . $query->rowCount() . ' รายการ', 'responseJSON' => $data));
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}