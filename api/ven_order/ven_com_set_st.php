<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$id = $data->id;
// http_response_code(200);
//         echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $data));
//         exit; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 

// The request is using the POST method
try{
    
    // if($data->action == 'set'){   
        $sql = "SELECT id,status status FROM `ven_com` WHERE id = :id LIMIT 1";
        $query = $dbcon->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if($result->status == 1){
            $sql = "UPDATE ven_com SET status = 0 WHERE id = $result->id";
            $query = $dbcon->prepare($sql);
            $dbcon->exec($sql);
        }else{
            $sql = "UPDATE ven_com SET status = 1 WHERE id = $result->id";
            $query = $dbcon->prepare($sql);
            $dbcon->exec($sql);
        }

        http_response_code(200);
        echo json_encode(array('status' => 'true', 'message' => 'successfully','data'=>$result));  
        
    // }    
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}