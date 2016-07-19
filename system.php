<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>统计</title>

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
        }
        $users = array();
        $dates = array();
        for($i=60; $i>=1; $i--) {
            $today = time();
            $timestr = "+".($i-60)." days";
            $cur = strtotime($timestr, $today);
            $datestr=date('Y-m-d', $cur);
            $dates[]=$datestr;
        }
        $sql = "SELECT * from user";
        $result=$conn->query($sql);
        while ($row=$result->fetch_assoc()) {
            if ($row['username']!='admin') {
                $users[]=$row['username'];
            }
        }
        //print_r($users);
        ?>
     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
       <div class="navbar-header">
          <a class="navbar-brand" href="#">加班么</a>
       </div>
       <div>
          <ul class="nav navbar-nav">
             <li class="active"><a href="#">统计</a></li>
             <li class=""><a href="forum.php">闲聊</a></li>
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
        <!--h1 style="text-align:center">统计</h1-->
        <p><strong>仅供参考</strong>以防止因忘记而产生错误冲突，如有疑问，去<a href="forum.php">闲聊</a>里面吐槽吧!</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>日期</th>
                    <?php
                    foreach($users as $i => $value) {
                        echo "<th>".$value."</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php for($i=1; $i<=60; $i++) {
                ?>
                <tr>
                    <?php
                    $datestr=$dates[$i-1];
                    echo "<td>".$datestr."</td>";
                    foreach($users as $b => $usertable) {
                        //for each user, there has only one possible match
                        $find=false;
                        foreach($users as $c => $userstr) {
                            $sql="SELECT * from $userstr WHERE date='$datestr' and username='$usertable'";
                            //echo $sql;
                            if ($result=$conn->query($sql)) {
                                if ($row=$result->fetch_assoc()) {
                                    $find=true;
                                    if ($user_name == $usertable && $user_name == $userstr) {
                                        echo "<td id=\"$i $b\" class=\"success cell\" style=\"color:#0000FF\">自用</td>";
                                    } else if ($userstr == $usertable) {
                                        echo "<td id=\"$i $b\" class=\"success cell\">自用</td>";
                                    } else {
                                        if ($userstr == $user_name) {
                                            echo "<td id=\"$i $b\" class=\"active cell\" style=\"color:#0000FF\">".$userstr."+</td>";
                                        } else {
                                            echo "<td id=\"$i $b\" class=\"active cell\">".$userstr."+</td>";
                                        }
                                    }
                                }
                            }
                        }
                        if ($find==false) {
                            echo "<td class=\"cell\" id=\"$i $b\"></td>";
                        }
                    }
                    ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php
            $conn->close();
        ?>
    </div>
      <script>
        $('.cell').click(function(e){
            var col = parseInt($(this).index())-1;
            var row = parseInt($(this).parent().index())+1;
            a=""+row+" "+col;
            var array=new Array();
            var datestr=new Array();
            var user=<?php echo "'$user_name';" ?>;
            <?php
                foreach($users as $c => $userstr) {
                    echo "array[$c]='$userstr';";
                }
                foreach($dates as $i => $curr) {
                    echo "datestr[$i]='$curr';";
                }
            ?>
            //update database in background
            $.ajax({
                'url':'updateCell.php',
                'data': {username:array[col], date:datestr[row-1], tablename:user},
                'type':'post',
                'dataType':'json',
                success:function(res){
                    if(res.pass==0) {
                        alert("错误: " + res['log']);
                        //alert($(this).innerHTML);
                        //window.location.reload();
                    } else if (res.pass==1) {
                        //alert("pass=1" + res['log']);
                        window.location.reload();
                    }
                },
                error: function(){
                    $(this).focus();
                    //alert("请求失败");
                    window.location.reload();
                }
            })
        });
      </script>
  </body>
</html>
