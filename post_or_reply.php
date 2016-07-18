<?php
    session_start();
    //connect to db
    $dbconfig = include 'dbconfig.php';
    $conn = new mysqli($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['dbname']);
    if ($conn->connect_error) {
        die("Error, connect db failed");
    } else {
        //echo "Connect db successed";
        $sql = "CREATE TABLE IF NOT EXISTS _post(time datetime NOT NULL, username char(255), checksum varchar(32) NOT NULL, reply varchar(32) NOT NULL, content TEXT NOT NULL)";
        if (!$result=$conn->query($sql)) {
            die("Error, create table failed");
        }
    }
    if (isset($_SESSION['username'])) {
        if (isset($_POST['content'])) {
            $content=$_POST['content'];
            //replace \n
            //$content=str_replace("<br>", "\r\n", $content);
            $username=$_SESSION['username'];
            $time = date('Y-m-d H:i:s');
            $md5 = md5($time." ".$username);
            $sql = "INSERT INTO _post values('$time', '$username', '$md5', md5('post'), '$content')";
            if ($result=$conn->query($sql)) {
                $conn->close();
                header("Location:forum.php");
            } else {
                $conn->close();
                die("Error insert:".$sql);
            }
        } else if (isset($_POST['reply']) && isset($_POST['md5'])) {
            $reply = $_POST['reply'];
            $md5 = $_POST['md5'];
            $username=$_SESSION['username'];
            $time = date('Y-m-d H:i:s');
            $reply_md5 = md5($time." ".$username);
            $sql = "INSERT INTO _post values('$time', '$username', '$reply_md5', '$md5', '$reply')";
            if ($result=$conn->query($sql)) {
                $conn->close();
                header("Location:forum.php");
            }
        } else {
            $conn->close();
            die("Something wrong");
        }
    }
?>