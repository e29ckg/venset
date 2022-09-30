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

$ven_users =array();

    // The request is using the POST method
    try{
        $sql = "SELECT * FROM profile WHERE status = 10 ORDER BY st ASC"; 
        $query = $dbcon->prepare($sql);
        $query->execute();
        $result_user = $query->fetchAll(PDO::FETCH_OBJ);

        if($u_role == 'ผู้พิพากษา'){

            $sql = "SELECT * FROM ven_user WHERE comment = 'ผู้พิพากษา' ORDER BY ven_user.order ASC"; 
            $query = $dbcon->prepare($sql);
            $query->execute();
            $result_ven_user = $query->fetchAll(PDO::FETCH_OBJ);

            foreach($result_ven_user as $rs_vu){
                array_push($ven_users,$rs_vu->user_id);
            }

            foreach($result_user as $rs_us){
                $ck = false;
                foreach($ven_users as $vu){
                    if($vu == $rs_us->user_id){
                        $ck = true; 
                    }
                }
                // for ($x = 0; $x < count($ven_users); $x++) {
                //     if($ven_users[$x] == $rs_us->user_id){
                //         $ck = true; 
                //     }else{
                //         $ck = false;
                //     }
                // }
                array_push($datas,array(
                    'user_id' => $rs_us->user_id,
                    'name'    => $rs_us->fname.$rs_us->name.' '.$rs_us->sname,
                    'ck'      => $ck
                ));
                // $ck = false;
            } 
            
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $datas,'vu'=>$result_user));
            exit;
            
        }
        // $sql = "SELECT * FROM ven_user as vu INNER JOIN `profile` as p ON p.user_id = vu.user_id  WHERE  p.status = 10 ORDER BY vu.order ASC";
        // $query = $dbcon->prepare($sql);
        // // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        // $query->execute();
        // $result = $query->fetchAll(PDO::FETCH_OBJ);

        // if($query->rowCount() > 0){                        //count($result)  for odbc
            
        // }
     
        http_response_code(200);
        echo json_encode(array('false' => true, 'massege' => 'ไม่พบข้อมูล '));
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}