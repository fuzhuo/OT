<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>修改密码</title>

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
        <h1 style="text-align:center">修改密码</h1>
        <?php
            if (isset($_SESSION['change_password_error']) && $_SESSION['change_password_error']) {
                echo "<p class=\"text-danger\" style=\"text-align:center\">原始密码错误</p>";
            }
        ?>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!-- script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <form role="form" class="form-horizontal" method="post" action="index.php">
            <div class="form-group">
                <div class="form-horizontal">
                    <label for="name" class="col-sm-4 control-label">原密码</label>
                    <div class="col-sm-4">
                        <input type="password" class="form-control" id="password" name="password" placeholder="输入当前密码">
                    </div>
                </div>
                <span id="checkpassword"></span>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label">新密码</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" id="password1" name="password1" placeholder="新密码">
                </div>
                <span id="checkpassword1"></span>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label">请再次输入</label>
                <div class="col-sm-4">
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="请再次输入新密码">
                </div>
                <span id="checkpassword2"></span>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-2">
                    <button type="submit" class="btn btn-success" id="button_reg" disabled="disabled">确认修改</button>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-default" onclick="javascript:window.location.href='index.php'">放弃修改</button>
                </div>
            </div>
        </form>
    </div>
    <!-- script used to check if file name already existed -->
    <script>
        $('#password').blur(function(){
            var name=$.trim($(this).val());
            if (name=='') {
                $('#checkpassword').css('color','red');
                $('#checkpassword').text("密码不能为空");
                $(this).focus();
            } else {
                $('#checkpassword').text("");
            }
        })
        $('#password1').bind('input propertychange', (function(){
            var name=$.trim($(this).val());
            var name2=$("input[id='password2']").val();
            var oripassword=$("input[id='password']").val();
            if (name==oripassword) {
                $('#checkpassword1').css('color','red');
                $('#checkpassword1').text("新密码不能和原密码相同");
                $('#button_reg').attr("disabled","disabled");
                return;
            } else {
                $('#checkpassword1').text("");
            }
            if (name2=='') return;
            if (name!=name2) {
                $('#checkpassword2').css('color','red');
                $('#checkpassword2').text("密码不一致");
                $('#button_reg').attr("disabled","disabled");
            } else {
                $('#checkpassword2').text("");
                $('#button_reg').removeAttr("disabled");
            }
        }))
        $('#password2').bind('input propertychange', (function(){
            var name=$.trim($(this).val());
            var name1=$("input[id='password1']").val();
            var oripassword=$("input[id='password']").val();
            if (name==oripassword) {
                $('#checkpassword2').css('color','red');
                $('#checkpassword2').text("新密码不能和原密码相同");
                $('#button_reg').attr("disabled","disabled");
                return;
            } else {
                $('#checkpassword1').text("");
            }
            if (name!=name1) {
                $('#checkpassword2').css('color','red');
                $('#checkpassword2').text("密码不一致");
                $('#button_reg').attr("disabled","disabled");
            } else {
                $('#checkpassword2').text("");
                $('#button_reg').removeAttr("disabled");
            }
        }))
    </script>
  </body>
</html>