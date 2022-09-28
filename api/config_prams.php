<?php

$price = [
    'กลางวัน' => [
        'ผู้พิพากษา'   => '3000',
        'ผอ./แทน'    => '1500',
        'จนท1'     => '1500',
        'จนท2'     => '1500',
        'จนท3'     => '1500',
        'จนท4'     => '1500',
        'จนท5'     => '1500'
    ],
    'กลางคืน' => [
        'ผู้พิพากษา'   => '2500',
        'จนท1'     => '1200',
        'จนท2'     => '1200',
        'จนท3'     => '1200',
        'จนท4'     => '1200',
        'จนท5'     => '1200'
    ],
];

$_DN  =  [
    'กลางวัน'=> '08:30',
    'กลางคืน'=> '16:30'
];

//ลำดับเวร      หน้าที่ตามคำสั่ง                                              //  Yii::$app->params['ven_time'];
$vt = [
     'ผู้พิพากษา'  => '00', 
     'ผอ./แทน'  => '01', 
     'จนท1'    => '02', 
     'จนท2'    => '03', 
     'จนท3'    =>'04', 
     'จนท4'    =>'05', 
     'จนท5'    =>'06', 
];