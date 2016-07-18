<?php
if (isset($_POST['username']) && isset($_POST['tablename']) && isset($_POST['date'])) {
    $username=$_POST['username'];//the column belongs to username
    $tablename=$_POST['tablename'];//tablename is current user
    $date=$_POST['date'];//date row
    //die(json_encode(array('pass'=>1, 'log'=>"测试成功".$username.",".$tablename.",".$date)));
    //connect to db
    $dbconfig = include 'dbconfig.php';
    $conn = new mysqli($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']);
    if ($conn->connect_error) {
        die(json_encode(array('pass'=>0, 'log'=>"数据库连接失败了".$conn->connect_error)));
    }
    //first check if this value already existed at other user table. if this is true, not allowed to change anything
    $users = array();
    $sql = "SELECT * from user";
    $result=$conn->query($sql);
    while ($row=$result->fetch_assoc()) {
        if ($row['username']!='admin' && $row['username']!=$tablename) {//except admin and self
            $users[]=$row['username'];
        }
    }
    foreach($users as $i_ => $table_) {//keep username is not existed in any table
        $sql = "SELECT * FROM $table_ WHERE username='$username' and date='$date'";
        if ($result=$conn->query($sql)) {
            if ($row=$result->fetch_assoc()) {
                die(json_encode(array('pass'=>0, 'log'=>"没有权限改变别人的项:".$table_)));
            }
        }
    }
    
    //next select if it belong to self, try update it
    $sql = "SELECT * FROM $tablename where username='$username' and date='$date'";
    if ($result=$conn->query($sql)) {
        if ($row=$result->fetch_assoc()) {
            //remove it, and we has permissions
            $sql = "DELETE FROM $tablename WHERE username='$username' and date='$date'";
            if ($result=$conn->query($sql)) {
                die(json_encode(array('pass'=>1, 'log'=>"成功从自己的表中移除:".$table_.":".$sql)));
            } else {
                die(json_encode(array('pass'=>0, 'log'=>"存在自己的表中，但是移除失败:".$tablename.":".$sql)));
            }
        }
    }
    //final means empty, try insert one to own table
    //try insert
    $sql = "INSERT INTO $tablename VALUE('$date', '$username')";
    if ($result=$conn->query($sql)) {
        die(json_encode(array('pass'=>1, 'log'=>"成功插入自己的表中".$sql)));
    } else {
        die(json_encode(array('pass'=>0, 'log'=>"不存在于任何表中，但是插入数据库失败:".$sql)));
    }
}
?>