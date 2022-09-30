<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data   = json_decode(file_get_contents("php://input"));
$id     = $data->id;
// http_response_code(200);
//         echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $data));
//         exit; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 

// The request is using the POST method
try{
    
    // if($data->action == 'delete'){   
        $sql = "SELECT id FROM ven_change 
                WHERE ven_id1 = $id OR ven_id2 = $id OR ven_id1_old = $id OR ven_id2_old = $id 
                LIMIT 1"; 
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':id',$data->id, PDO::PARAM_INT);
        $query->execute();
        // $model_ven_DIS = $queryVen->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() >0){
            http_response_code(200);
            echo json_encode(array('status' => 'false', 'message' => 'เวรนี้ไม่สามารถได้เนื่องจากมีการนำไปใช้ในใบเปลี่ยนเวร')); 
            exit;
        }else{
            $sql = "DELETE FROM ven WHERE id = $id";
            $dbcon->exec($sql);
            http_response_code(200);
            echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully'));              
            exit;
        }         
    // }    
        
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}