<html>
<head><title>Login</title></head>
<body>
User Login<br>
<form action="login.php" method="post">
Username: <input type="text" name="username" /><br>
Password: <input type="password" name="password" /><br>
<input type="submit" value="Login"/>
<?php
// Turn off all error reporting
//error_reporting(0);

session_start();

// Create connection
$sqlConnect = mysqli_connect('localhost', 'root', '', 'location_commerce_db', '3306','');
  mysqli_select_db($sqlConnect, 'location_commerce_db');
// Check connection
if (!$sqlConnect) {
  die("Connection failed: " . $sqlConnect->connect_error);
}

$sqlQuery = "SELECT u.username, u.password from  USER u";

$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
$username_log = $_POST["username"];
$password_log = $_POST["password"];
$session_username = NULL;
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $us= $sqlRow['username'];
  $pass = $sqlRow['password'];
  if($username_log == $us && $password_log == $pass){
    $_SESSION['username'] = $username_log;
  }
}
mysqli_close($sqlConnect);

//login conditions
if($_SESSION['username']!=NULL){
  header("Location: home.php");
  die();
}
else{
  echo "wrong or unregistered user";
  //bot checker security
  if (!isset($_SESSION['bot_count'])) {
    $_SESSION['bot_count'] = 0;
  }
  $_SESSION['bot_count']++;
  if($_SESSION['bot_count'] > 3){
    header("Location: login_bot.php");
    die();
  }
}

?>
</body>
</html>
