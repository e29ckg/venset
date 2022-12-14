<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));

// http_response_code(200);
//         echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $data));
//         exit; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 

// The request is using the POST method
try{
    
    if($data->action == 'delete'){   
        $sql = "SELECT id FROM ven WHERE ven_com_id = :ven_com_id LIMIT 1"; 
        $queryVen = $dbcon->prepare($sql);
        $queryVen->bindParam(':ven_com_id',$data->id, PDO::PARAM_INT);
        $queryVen->execute();
        // $model_ven_DIS = $queryVen->fetchAll(PDO::FETCH_OBJ);
        if($queryVen->rowCount() >0){
            http_response_code(200);
            echo json_encode(array('status' => 'false', 'message' => 'จัดเวรเข้าคำสั่งนี้แล้ว'));  
            
        }else{
            $sql = "DELETE FROM ven_com WHERE id = $data->id";
            $dbcon->exec($sql);
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully'));              
        }         
    }    
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}