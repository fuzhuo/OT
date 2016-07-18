<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>水吧</title>

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
    //db format create table _post(time datetime, author char(255), title TEXT, content TEXT);
    $conn = new mysqli('192.168.2.246','root','vmmvmm','ot');
    if ($conn->connect_error) {
        die("Error, connect db failed");
    } else {
        //echo "Connect db successed";
    }
    //print_r($users);
    ?>
     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
       <div class="navbar-header">
          <a class="navbar-brand" href="#">加班么</a>
       </div>
       <div>
          <ul class="nav navbar-nav">
             <li class=""><a href="system.php">OT统计</a></li>
             <li class="active"><a href="#">闲聊</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
             <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                   <?php echo "当前用户:".$user_name; ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                   <li><a href="logout.php">登出</a></li>
                </ul>
             </li>
          </ul>
       </div>
    </nav>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">这个是标题 zfu 2016-07-22 18:40</h3>
                </div>
                <div class="panel-body">
                <p>内容就是这些啦，你赶快布局一下</p>
                </div>
            </div>
        </div>
    <?php $conn->close(); ?>
  </body>
</html>
