<?php
    if (!isset($_POST['username'])) exit();
    $name=$_POST['username'];
    if ($name=="user") {
        die(json_encode(array('pass'=>0, 'love'=>"已经有名为user的表，不能建立")));
    }
    $conn = new mysqli('192.168.2.246','root','vmmvmm','ot');
    if ($conn->connect_error) {
        die(json_encode(array('pass'=>2, 'love'=>"连接失败".$conn->connect_error)));
    }
    $sql = "SELECT * FROM user where username='$name'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        if ($row = $result->fetch_assoc()) {
            $ans="username:".$row['username'];
            $conn->close();
            die(json_encode(array('pass'=>0, 'love'=>$sql." ".$ans)));
        } else {
            $conn->close();
            die(json_encode(array('pass'=>1, 'love'=>$sql." row is null")));
        }
    }
    $conn->close();
    die(json_encode(array('pass'=>1, 'love'=>$sql)));
?>