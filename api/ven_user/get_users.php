<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$u_role = $data->u_role;
$DN     = $data->DN;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
/**
 *           
 */
$datas = array();

    // The request is using the POST method
    try{
        if($u_role == 'ผู้พิพากษา'){
            $sql = "SELECT * FROM ven_user as vu INNER JOIN `profile` as p ON p.user_id = vu.user_id  WHERE vu.comment = '$u_role' AND  p.status = 10 ORDER BY vu.order ASC"; 
                    
        }
        // $sql = "SELECT * FROM ven_user as vu INNER JOIN `profile` as p ON p.user_id = vu.user_id  WHERE  p.status = 10 ORDER BY vu.order ASC";
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0){                        //count($result)  for odbc
            
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $result));
            exit;
        }
     
        http_response_code(200);
        echo json_encode(array('false' => true, 'massege' => 'ไม่พบข้อมูล '));
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}