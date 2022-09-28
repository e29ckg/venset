<?php

    $servername = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "main";

    try{
        $dbcon = new PDO("mysql:host=$servername;dbname=$dbname", $username, $userpass);
        // set the PDO error mode to exception
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }catch(PDOException $e){
        echo "Faild to connect to database" . $e->getMessage();
    }


?>