<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../config_db.php";

$data = json_decode(file_get_contents("php://input"));
$ven_com_id = (integer)$data->ven_com_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
/**
 *           
 */
$datas = array();

    // The request is using the POST method
    try{
        $sql_vc = "SELECT u_role FROM ven_com WHERE id = $ven_com_id";
        $query_vc = $dbcon->prepare($sql_vc);
        $query_vc->execute();
        $res_vc = $query_vc->fetch(PDO::FETCH_OBJ);
        $u_role = $res_vc->u_role;

        if($u_role == 'ผู้พิพากษา'){
            $sql = "SELECT user_id, fname, name, sname, dep, st FROM profile WHERE dep LIKE 'ผู้พิพากษา%' AND status = 10 ORDER BY st ASC";
        }elseif($u_role == 'ผอ./แทน'){
            $sql = "SELECT user_id, fname, name, sname, dep, st FROM profile WHERE dep LIKE 'ผู้อำนวยการ%' OR dep LIKE '%พิเศษ' AND status = 10 ORDER BY st ASC";
        }else{
            $sql = "SELECT user_id, fname, name, sname, dep, st FROM profile WHERE dep NOT LIKE '%พิเศษ' AND dep NOT LIKE 'ผู้พิพากษา%' AND status = 10 ORDER BY st ASC";
        }

        $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0){                        //count($result)  for odbc
            foreach($result as $rs){
                array_push($datas, array(
                    'id' => $rs->user_id,
                    'uid' => $rs->user_id,
                    'name' => $rs->fname.$rs->name . ' ' .$rs->sname,
                ));
            }
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $datas,'$u_role '=>$u_role));
            exit;
        }
        http_response_code(200);
        echo json_encode(array('false' => true, 'massege' => 'ไม่พบข้อมูล','respJSON' => $datas));
    
     
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}