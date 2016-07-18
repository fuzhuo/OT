<?php
//This file is using to check login
session_start();

if (isset($_SESSION['username'])) {
    header("Location:system.php");
    exit();
} else if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {
    //add user here, need create a table for this user
    $conn = new mysqli('192.168.2.246','root','vmmvmm','ot');
    if ($conn->connect_error) {
        die("连接失败".$conn->connect_error);
        header("Location:error_connect_db.php");
    }
    $username=$_POST['username'];
    $password1=$_POST['password1'];
    $password2=$_POST['password2'];
    $sql = "insert into user(username, password) value('$username', '$password1')";
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
    $conn = new mysqli('192.168.2.246','root','vmmvmm','ot');
    if ($conn->connect_error) {
        die("连接失败".$conn->connect_error);
    }
    $username=$_POST['username'];
    $password=$_POST['password'];
    $sql="select * from user where username='$username' and password='$password'";
    if ($result=$conn->query($sql)) {
        if($row=$result->fetch_assoc()) {
            $user_name=$row['username'];
            $_SESSION['username']=$username;
            $_SESSION['password']=$password;
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