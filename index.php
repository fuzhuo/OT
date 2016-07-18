<?php
//This file is using to check login
session_start();

$conn = new mysqli('127.0.0.1','root','vmmvmm','ot');
if ($conn->connect_error) {
    die("连接失败".$conn->connect_error);
    header("Location:error_connect_db.php");
} else {
    $sql = "CREATE TABLE if not exists user(username char(255), password char(255))";
    if (!$result=$conn->query($sql)) {
        die("没有user数据库，而且创建失败了");
    }
}
if (isset($_SESSION['username'])) {
    header("Location:system.php");
    exit();
} else if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {
    //add user here, need create a table for this user
    $username=$_POST['username'];
    $password1=$_POST['password1'];
    $password2=$_POST['password2'];
    $md5=md5($password1);
    $sql = "insert into user(username, md5) value('$username', '$md5')";
    $result = $conn->query($sql);
    if ($result) {
        $_SESSION['username']=$username;
    } else {
        $conn->close();
        header("Location:error_insert_user.php");
    }
    //add a table named with username
    $sql = "CREATE TABLE ".$username."(date date, username char(255))";
    $result = $conn->query($sql);
    if (!$result) {
        $conn->close();
        header("Location:create_table_error.php");
        eixt();
    }
    header("Location:system.php");
    $conn->close();
} else if (isset($_POST['username']) && isset($_POST['password'])) {
    $username=$_POST['username'];
    $password=$_POST['password'];
    $md5 = md5($password);
    $sql="select * from user where username='$username' and md5='$md5'";
    if ($result=$conn->query($sql)) {
        if($row=$result->fetch_assoc()) {
            $user_name=$row['username'];
            $_SESSION['username']=$username;
            $_SESSION['login_error']=FALSE;
            echo "Login ok";
            $conn->close();
            header("Location:system.php");
        } else {
            header("Location:login.php");
            echo "Login failed";
            $_SESSION['login_error']=TRUE;
            exit();
        }
    } else {
        header("Location:login.php");
        $_SESSION['login_error']=TRUE;
        echo "Login failed";
        exit();
    }
    exit();
} else {
    header("Location:login.php");
    exit();
}
?>