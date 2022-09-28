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
/**
 * data = [
 *      {'ven_month':'','ven_com_num':[]
 *
 * 
 *  }
 * ]
 */
$datas = array();


    // The request is using the POST method
    try{
        $sql = "SELECT * FROM ven_com GROUP BY ven_month ORDER BY ven_month DESC LIMIT 0,5";
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0){                        //count($result)  for odbc
            foreach($result as $rs){
                $sql = "SELECT * FROM ven_com WHERE ven_month = :ven_month GROUP BY ven_com_num ORDER BY ven_com_num ASC";
                $query = $dbcon->prepare($sql);
                $query->bindParam(':ven_month',$rs->ven_month, PDO::PARAM_STR);
                $query->execute();
                $model_nums = $query->fetchAll(PDO::FETCH_OBJ);               
                
                $ven_com_num = array();
                foreach ($model_nums as $model_num){   
                    
                    $sql = "SELECT * FROM ven_com WHERE ven_month = :ven_month AND ven_com_num = :ven_com_num  ORDER BY ven_time ASC";
                    $query = $dbcon->prepare($sql);
                    $query->bindParam(':ven_month',$rs->ven_month , PDO::PARAM_STR);
                    $query->bindParam(':ven_com_num',$model_num->ven_com_num , PDO::PARAM_STR);
                    $query->execute();
                    $model_ven_com_nums = $query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0){
                        $ven_com = array();
                        foreach($model_ven_com_nums as $cm){
                            $sql = "SELECT id FROM ven WHERE ven_com_id = :ven_com_id LIMIT 0,1"; 
                            $query = $dbcon->prepare($sql);
                            $query->bindParam(':ven_com_id',$model_num->id, PDO::PARAM_STR);
                            $query->execute();
                            // $model_ven_DIS = $query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() == 0){
                                $status_del = true;
                            }else{
                                $status_del = false;
                            } 
                            $st = $cm->status == 1 ? true : false ;
                            array_push($ven_com,array(
                                'ven_com_num'   => $cm->ven_com_num,
                                'id'            => $cm->id,
                                'ven_com_date'  => $cm->ven_com_date,
                                'ven_time'      => $cm->ven_time,
                                'DN'            => $cm->DN,
                                'ven_month'     => $cm->ven_month,
                                'u_role'        => $cm->u_role,
                                'ven_com_name'  => $cm->ven_com_name,
                                'price'         => $cm->price,
                                'color'         => $cm->color,
                                'comment'       => $cm->comment,
                                'status'        => $st,
                                'status_del'    => $status_del
                            ));                    
                        }  
                    
                    }

                    array_push($ven_com_num,array(
                        'ven_com_num'   => $model_num->ven_com_num,
                        'ven_com_date'  => $model_num->ven_com_date,
                        'ven_com'       => $ven_com
                    ));   

                                      
                }
                array_push($datas,array(
                    'ven_month' => $rs->ven_month,
                    'ven_com_num' => $ven_com_num,
                ));
            }
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $datas));
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