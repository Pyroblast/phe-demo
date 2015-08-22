<?php
session_start();
$str = "phe2015";
$token = md5($str);
$_SESSION['token'] = $token;
if (empty($_POST['token_now'])) {
	echo "  <!DOCTYPE html>
            <html>
            <head>
            <meta charset='utf-8'>
            <link rel='shortcut icon' type='image/png' href='favicon.ico'>
            <title>PHE Tools</title>
            </head>
            <script language='javascript'>alert('邀请码不能为空……点击返回');window.location.href='login.html';</script>
            <body>
            </body>
            </html>";
    die();
}
$token_now = addslashes($_POST['token_now']);
$_SESSION['token_now'] = md5($token_now);


if ($_SESSION['token_now'] == $_SESSION['token']) {
	$_SESSION['token_now'] = md5($token_now);
	header("Location:index.php");	
} else {
        echo "<!DOCTYPE html>
        <html>
        <head>
        <meta charset='utf-8'>
        <link rel='shortcut icon' type='image/png' href='favicon.ico'>
        <title>PHE Tools</title>
        </head>
        <script language='javascript'>alert('邀请码错误……请重新输入');window.location.href='login.html';</script>
        <body>
        </body>
        </html>";
}
?>