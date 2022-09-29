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
/**
 * data = [
 *      {'ven_month':'','ven_com_num':[]
 *
 * 
 *  }
 * ]
 */
$datas = array();
$ven_com        = $data->ven_com; 
$ven_com_num    = $ven_com->ven_com_num;
$ven_com_date   = $ven_com->ven_com_date;
$ven_month      = $ven_com->ven_month;
$ven_com_detail = $ven_com->ven_com_detail;
$action         = $ven_com->act;
// $pric = 0;
$ven_time ='';
// The request is using the POST method
try{
    if($action == 'insert'){
        foreach($ven_com_detail as $vcd){
            // array_push($datas,array('u_role'=>$vcd->u_role));
            $sql = "SELECT id FROM `ven_com` WHERE ven_com_num = :ven_com_num AND ven_com_date = :ven_com_date AND ven_month = :ven_month AND u_role =:u_role LIMIT 1";
            $query = $dbcon->prepare($sql);
            $query->bindParam(':ven_com_num',$ven_com_num, PDO::PARAM_STR);
            $query->bindParam(':ven_com_date',$ven_com_date, PDO::PARAM_STR);
            $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);
            $query->bindParam(':u_role',$vcd->u_role, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);

            if($result){
                // $pric = $price['กลางวัน']['ผู้พิพากษา'];
                // $ven_time = $_DN[$vcd->DN].':'.$vt[$vcd->u_role]; 
                $sql = "UPDATE ven_com SET 
                                ven_com_num     =:ven_com_num, 
                                ven_com_date    =:ven_com_date,
                                ven_month       =:ven_month, 
                                ven_time        =:ven_time, 
                                DN              =:DN, 
                                u_role          =:u_role, 
                                ven_com_name    =:ven_com_name,
                                price           =:price,
                                status          = 1
                            WHERE id = :id";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ven_com_num',$ven_com_num, PDO::PARAM_STR);
                $query->bindParam(':ven_com_date',$ven_com_date, PDO::PARAM_STR);
                $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);

                $query->bindParam(':ven_time',$vcd->ven_time, PDO::PARAM_STR);
                $query->bindParam(':DN',$vcd->DN, PDO::PARAM_STR);
                $query->bindParam(':u_role',$vcd->u_role, PDO::PARAM_STR);
                $query->bindParam(':ven_com_name',$vcd->ven_com_name, PDO::PARAM_STR);
                $query->bindParam(':price',$vcd->price, PDO::PARAM_STR);
                $query->bindParam(':id',$result->id, PDO::PARAM_INT);
                $query->execute();

            }else{
                // $ven_time = $_DN[$vcd->DN].':'.$vt[$vcd->u_role]; 
                $sql = "INSERT INTO ven_com(ven_com_num, ven_com_date, ven_month, ven_time, DN, u_role, ven_com_name, price, status) 
                                  VALUE(:ven_com_num, :ven_com_date, :ven_month, :ven_time, :DN, :u_role, :ven_com_name, :price, 1);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ven_com_num',$ven_com_num, PDO::PARAM_STR);
                $query->bindParam(':ven_com_date',$ven_com_date, PDO::PARAM_STR);
                $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);
                $query->bindParam(':ven_time',$vcd->ven_time, PDO::PARAM_STR);
                $query->bindParam(':DN',$vcd->DN, PDO::PARAM_STR);
                $query->bindParam(':u_role',$vcd->u_role, PDO::PARAM_STR);
                $query->bindParam(':ven_com_name',$vcd->ven_com_name, PDO::PARAM_STR);
                $query->bindParam(':price',$vcd->price, PDO::PARAM_STR);
                $query->execute();
            }
            
        }

        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $datas));
        exit;                
    }    
    if($action == 'update'){
        foreach($ven_com_detail as $vcd){
            if(isset($vcd->id)){
                // array_push($datas,array(
                //     $vcd->id . ' | '
                // ));
                $sql = "UPDATE ven_com SET 
                    ven_com_num     =:ven_com_num, 
                    ven_com_date    =:ven_com_date,
                    ven_month       =:ven_month, 
                    ven_time        =:ven_time, 
                    DN              =:DN, 
                    u_role          =:u_role, 
                    ven_com_name    =:ven_com_name,
                    price           =:price,
                    status          = 1
                WHERE id = :id";   

                $query = $dbcon->prepare($sql);
                $query->bindParam(':ven_com_num',$ven_com->ven_com_num, PDO::PARAM_STR);
                $query->bindParam(':ven_com_date',$ven_com->ven_com_date, PDO::PARAM_STR);
                $query->bindParam(':ven_month',$ven_com->ven_month, PDO::PARAM_STR);
                
                $query->bindParam(':ven_time',$vcd->ven_time, PDO::PARAM_STR);
                $query->bindParam(':DN',$vcd->DN, PDO::PARAM_STR);
                $query->bindParam(':u_role',$vcd->u_role, PDO::PARAM_STR);
                $query->bindParam(':ven_com_name',$vcd->ven_com_name, PDO::PARAM_STR);
                $query->bindParam(':price',$vcd->price, PDO::PARAM_STR);

                $query->bindParam(':id',$vcd->id, PDO::PARAM_INT);
                $query->execute();

            }else{
                // array_push($datas,array(
                //     ' No-id | '
                // ));
                $sql = "INSERT INTO ven_com(ven_com_num, ven_com_date, ven_month, ven_time, DN, u_role, ven_com_name, price, status) 
                                  VALUE(:ven_com_num, :ven_com_date, :ven_month, :ven_time, :DN, :u_role, :ven_com_name, :price, 1);";        
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ven_com_num',$ven_com_num, PDO::PARAM_STR);
                $query->bindParam(':ven_com_date',$ven_com_date, PDO::PARAM_STR);
                $query->bindParam(':ven_month',$ven_month, PDO::PARAM_STR);
                $query->bindParam(':ven_time',$vcd->ven_time, PDO::PARAM_STR);
                $query->bindParam(':DN',$vcd->DN, PDO::PARAM_STR);
                $query->bindParam(':u_role',$vcd->u_role, PDO::PARAM_STR);
                $query->bindParam(':ven_com_name',$vcd->ven_com_name, PDO::PARAM_STR);
                $query->bindParam(':price',$vcd->price, PDO::PARAM_STR);
                $query->execute();
            }

        }    

            
         

        http_response_code(200);
        echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $datas));
        exit;                
    }  
    
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

