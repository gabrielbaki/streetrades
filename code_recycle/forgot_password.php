<?php
// Turn off all error reporting
error_reporting(0);

include("connect.php");
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
  <p id="header-title">FORGOT PASSWORD</p>
<form action="forgot_password.php" method="post">
Username: <input type="text" name="username" /><br>
Enter the code below: <input type="text" name="captcha" /><br><br>
<?php
include("simple-php-captcha-master/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
?>
<br><br>
<input type="submit" value="Send Password Reset to Email"/>

<?php
//check talent table
$sqlQuery = "SELECT t.username, t.email from  USER t";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
$username_log = $_POST['username'];
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $us= $sqlRow['username'];
  if($username_log == $us){
    $usEmail = $sqlRow['email'];
  }
}

if($usEmail != NULL && $_POST['captcha']==$_SESSION['captcha_user_input']['code']){
  echo "email regarding details of your account has been sent to : ".$usEmail;

  //dont work locally
  mail("gabrielbaky@gmail.com","Test","test");
}
else{
  echo "please fill in the information required correctly";
}

$_SESSION['captcha_user_input'] = $_SESSION['captcha'];

mysqli_close($sqlConnect);
?>
</div>
</body>
</html>
