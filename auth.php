<?php
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>本站开启了密码保护</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.bootcss.com/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div class="container text-center">
    <div class="jumbotron">
        <h1><span class="glyphicon glyphicon-eye-close"></span></h1>
        <h2>本站开启了密码保护</h2>
        <p><input class="form-control" type="password" name="physton_pass" placeholder="请输入密码，继续浏览网站!" style="width: 50%;display: inline-block"></p>
        <p><button type="button" class="btn btn-primary btn-submit">提交密码</button></p>
    </div>
</div>

<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
<script type="text/javascript">
    $(function() {
        function submit() {
            var url = window.location.href;
            $.ajax({
                url: url,
                type: "GET",
                data: {physton_pass: $("input[name='physton_pass']").val()},
                success: function(data) {
                    if(data == "success"){
                        window.location.reload();
                    }
                }
            })
        }
        $(".btn-submit").on("click", function() {
            submit();
        });
        $("input[name='physton_pass']").on("keydown", function(e) {
            if(e.keyCode == 13) {
                submit();
            }
        });
    });
</script>
</html>
HTML;

$password_file = __DIR__ . '/password.php';
if (file_exists($password_file)) {
    $password = include($password_file);
} else {
    $password = '';
}
if (!empty($password)) {
    if (empty($_COOKIE['PHYSTON_PASS']) || $_COOKIE['PHYSTON_PASS'] != md5($password)) {
        if (empty($_GET['physton_pass'])) {
            echo $html;
            exit();
        } else {
            if ($_GET['physton_pass'] == $password) {
                setcookie('PHYSTON_PASS', md5($password), time() + 3600 * 24);
                echo 'success';
                exit;
            } else {
                echo 'error';
                exit;
            }
        }
    }
}
