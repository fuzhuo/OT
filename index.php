<?php
//This file is using to check login
session_start();

//connect to db
$dbconfig = include 'dbconfig.php';
$conn = new mysqli($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']);
if ($conn->connect_error) {
    die("连接失败".$conn->connect_error);
    header("Location:error_connect_db.php");
} else {
    $sql = "CREATE DATABASE IF NOT EXISTS ot";
    if (!$result=$conn->query($sql)) {
        die("没有ot数据库，而且创建失败了");
    }
    $sql = "CREATE TABLE if not exists user(username char(255), md5 char(255))";
    if (!$result=$conn->query($sql)) {
        die("没有user表，而且创建失败了");
    }
}

if (isset($_POST['password']) && isset($_POST['password1']) && isset($_POST['password2'])) {
    $password = $_POST['password'];
    $newpassword = $_POST['password1'];
    $username=$_SESSION['username'];
    
    $md5 = md5($password);
    $sql="select * from user where username='$username' and md5='$md5'";
    if ($result=$conn->query($sql)) {
        if($row=$result->fetch_assoc()) {
            $md5 = md5($newpassword);
            $sql = "UPDATE user set md5='$md5' where username='$username'";
            if ($result_change=$conn->query($sql)) {
                $_SESSION['change_password_error']=0;
                header("Location:index.php");
                exit();
            }
        }
    }
    $_SESSION['change_password_error']=1;
    header("Location:changepassword.php");
} else if (isset($_SESSION['username'])) {
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
        die("ERROR insert user:".$sql);
    }
    //add a table named with username
    $sql = "CREATE TABLE ".$username."(date date, username char(255))";
    $result = $conn->query($sql);
    if (!$result) {
        $conn->close();
        die("create table error:".$sql);
    }
    header("Location:system.php");
    $conn->close();
} else if (isset($_POST['username']) && isset($_POST['password'])) {
    //Login check
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
