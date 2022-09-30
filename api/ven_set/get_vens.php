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
            id: 'a',
            title: 'my event',
            start: '2022-09-01',
            extendedProps: {
                uid: 5555,
                uname: '',
                ven_date: '',
                ven_time: '',
                DN: '',
                ven_month: '',
                ven_com_id: '',
                st: '',

            }
 */
$datas = array();


    // The request is using the POST method
    try{
        $sql = "SELECT v.id, v.ven_date, v.ven_time, p.name FROM ven as v 
        INNER JOIN `profile` as p ON v.user_id = p.user_id
        WHERE v.status = 2 AND p.`status` = 10
        ORDER BY v.ven_date DESC
        LIMIT 200";
        $query = $dbcon->prepare($sql);
        // $query->bindParam(':kkey',$data->kkey, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0){                        //count($result)  for odbc
            foreach($result as $rs){
                array_push($datas,array(
                    'id'    => $rs->id,
                    'title' => $rs->name,
                    'start' => $rs->ven_date.' '.$rs->ven_time,

                ));
            }
            http_response_code(200);
            echo json_encode(array('status' => true, 'massege' => 'สำเร็จ', 'respJSON' => $datas));
            exit;
        }
     
        http_response_code(200);
        echo json_encode(array('status' => true, 'massege' => 'ไม่พบข้อมูล ', 'respJSON' => $datas));
    
    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
        http_response_code(400);
        echo json_encode(array('status' => false, 'massege' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
    }
}