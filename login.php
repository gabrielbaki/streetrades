<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title> Streetrades </title>
  <link rel="stylesheet" type="text/css" href="normalize.css">
  <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link rel="icon" type="image/png" href="/images/talntalogo.png"/>
</head>
<body>
  <div class="header">
    <img src="images/flockmart.png" alt="FLockMart"/>
  </div>
<div class="login">
<p id="header-title">LOGIN</p>
<form action="login.php" method="post">
<label for="username">Username</label> <br> <input type="text" name="username" /><br>
<label for="password">Password</label> <br> <input type="password" name="password" /><br>
<label for="captcha">Captcha</label> <br> <input type="text" name="captcha" /><br><br>
<?php
session_start();
include("simple-php-captcha-master/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
?>
<br>
<input type="submit" value="Login"/>
<br><br>
<?php
// Turn off all error reporting
error_reporting(0);

include("connect.php");

include("functions.php");

//login conditions
if(usernameAndPasswordExist($_POST['username'], $_POST['password'], $sqlConnect)
&& $_SESSION['captcha_user_input']['code']==$_POST['captcha']){
  $_SESSION['username'] = $_POST['username'];
  header("Location: get_location.php");
  die();
}
else if ($_POST['username']==NULL || $_POST['password']==NULL || $_POST['captcha']==NULL){
  echo "all fields must be filled";
}
else if ($_SESSION['captcha_user_input']['code'] != $_POST['captcha']) {
  echo "wrong captcha";
}
else{
  echo "wrong or unregistered user";
}

$_SESSION['captcha_user_input'] = $_SESSION['captcha'];

mysqli_close($sqlConnect);

?>

<br><br>
<a href="register_option.php">Register</a> <br>
<a href="forgot_password.php">Forgot Password</a> <br>
</div>
</body>
</html>
