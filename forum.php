<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>闲聊</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="customize.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view tde page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location:login.php");
        exit();
    }
    $user_name=$_SESSION['username'];
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
    ?>
     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
       <div class="navbar-header">
          <a class="navbar-brand" href="#">加班么</a>
       </div>
       <div>
          <ul class="nav navbar-nav">
             <li class=""><a href="system.php">统计</a></li>
             <li class="active"><a href="#">闲聊</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
             <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   <?php echo "当前用户:".$user_name; ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                   <li><a href="changepassword.php">修改密码</a></li>
                   <li><a href="logout.php">登出</a></li>
                </ul>
             </li>
          </ul>
       </div>
    </nav>
    <script src="jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
        <div class="container">
            <?php
            $sql="select * from _post where reply=md5('post') order by time desc";//Only for post
            if ($result=$conn->query($sql)) {
                while ($row=$result->fetch_assoc()) {
            ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-sm-1">
                        <?php echo $row['username']; ?>
                        </div>
                        <div class="col-sm-offset-7 col-sm-4 text-right">发布于 <?php echo $row['time'];?></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="post-content">
                        <p><?php echo nl2br($row['content']); ?></p>
                    </div>
                    <hr>
                    <div class="row">
                        <?php //loop here to show replys
                        $post_md5=md5($row['time']." ".$row['username']);
                        $sql_reply="select * from _post where reply='$post_md5'";
                        //echo $sql_reply;
                        if ($result_reply=$conn->query($sql_reply)) {
                            while ($row_reply=$result_reply->fetch_assoc()) {
                        ?>
                        <div class="col-sm-1"><strong><?php echo $row_reply['username'] ?>:</strong></div>
                        <div><?php echo nl2br($row_reply['content']) ?></div>
                        <?php
                            }//while
                        }//if
                        ?>
                    </div>
                    <form role="form" class="row post-reply" method="post" action="post_or_reply.php">
                        <div class="form-group col-sm-11">
                            <input type="text" name="reply" class="form-control" placeholder="输入来回复">
                            <input type="hidden" name="md5" value=<?php echo "$post_md5"; ?>>
                        </div>
                        <div class="form-group col-sm-1">
                            <button class="btn btn-primary pull-right" type="submit">回复</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
                }//while
            }//if
            ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" class="row" method="post" action="post_or_reply.php">
                        <div class="form-group col-sm-12">
                            <textarea class="post-new" placeholder="请输入新话题内容" type="submit" name="content"></textarea>
                        </div>
                        <div class="form-group col-sm-offset-5 col-sm-1">
                            <button class="btn btn-primary" type="submit">发表新话题</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php $conn->close(); ?>
  </body>
</html>
