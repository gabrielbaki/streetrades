<?php
// Turn off all error reporting
error_reporting(0);

include("check_session.php");

include("connect.php");

include("functions.php");
?>
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
  <a href="home.php">| Home |</a>
  <a href="profile.php">| My Profile |</a>
  <a href="post.php">| Sell Item |</a>
  <a href="message.php">| Chat |</a>
  <a href="logout.php">| Logout |</a> <br>
  <p id="header-title">CHANGE PASSWORD</p>
<form action="change_password.php" method="post">
Old Password: <input type="password" name="old_password" /><br>
New Password: <input type="password" name="new_password" /><br>
Re Enter New Password: <input type="password" name="re_new_password" /><br><br>
Enter the code below: <input type="text" name="captcha" /><br><br>
<?php
include("simple-php-captcha-master/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
?>
<br>
<input type="submit" value="Change Password"/><br>
<br>

<?php
if(usernameAndPasswordExist($_SESSION['username'], $_POST['old_password'], $sqlConnect)
&& $_POST['new_password']==$_POST['re_new_password'] && $_POST['captcha']==$_SESSION['captcha_user_input']['code']){
  $sqlUpdate = "UPDATE USER SET password='".$_POST['new_password']."' WHERE username='".$_SESSION['username']."'";
    mysqli_query($sqlConnect, $sqlUpdate);
    echo "password of ".$_SESSION['username']." successfully updated";
}
else {
  echo "please fill in the information required correctly";
}

$_SESSION['captcha_user_input'] = $_SESSION['captcha'];

mysqli_close($sqlConnect);
?>
<br><br>
<a href="forgot_password.php">forgot password</a><br>

</div>
</body>
</html>
