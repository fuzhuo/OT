<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>登录</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view tde page via file:// -->
    <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo "<p>已经登录".$_SESSION['username']."</p>";
            } else {
                echo "<p>请登录</p>";
            }
        ?>
        <script src="jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <h1 style="text-align:center">加班么</h1>
        <?php
            if (isset($_SESSION['login_error']) && $_SESSION['login_error']) {
                echo "<p class=\"text-danger\" style=\"text-align:center\">用户名或密码错误</p>";
            }
        ?>
        <form role="form" method="post" class="form-horizontal" action="index.php">
            <div class="form-group">
                <div class="form-horizontal">
                    <label class="col-sm-4 control-label">用户名</label>
                    <div class="col-sm-4">
                        <input type="text" id="username" class="form-control" name="username" placeholder="用户名">
                    </div>
                    <span id="checkusername" style="color: #FF0000"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">密码</label>
                <div class="col-sm-4">
                    <input type="password" id="password" class="form-control" name="password" placeholder="密码">
                </div>
                <span id="checkpassword" style="color:#FF0000"></span>
            </div>
            <div class="form-group">
                <!--
                <div class="col-sm-offset-4 col-sm-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox">记住我
                        </label>
                    </div>
                </div>
                -->
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-1">
                    <button type="submit" id="login_button" class="btn btn-default" disabled="disabled">登录</button>
                </div>
                <div class="col-sm-offset-1 col-sm-2">
                    <button type="button" class="btn btn-success" onclick="javascript:window.location.href='regester.php'">注册</button>
                </div>
            </div>
        </form>
    </div>
    <script>
    checkLogin = function() {
        var username=$("input[id='username']").val();
        var password=$("input[id='password']").val();
        if (username.length==0) {
            $('#checkusername').text("用名不能为空");
        } else {
            $('#checkusername').text("");
        }
        if (password.length==0) {
            $('#checkpassword').text("密码不能为空");
        } else {
            $('#checkpassword').text("");
        }
        if (username.length!=0 && password.length!=0) {
            $('#login_button').removeAttr("disabled");
        } else {
            $('#login_button').attr("disabled","disabled");
        }
    }
    $('#username').bind('input propertychange', checkLogin);
    $('#password').bind('input propertychange', checkLogin);
    </script>
  </body>
</html>
