<html>
<head>
  <meta charset="utf-8">
  <title> Streetrades </title>
  <link rel="stylesheet" type="text/css" href="normalize.css">
  <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  <div class="header">
      <img src="images/flockmart.png" alt="FlockMart"/>
    </div>
    <div class="login">
  <p id="header-title">REGISTER</p><br>
<form action="register.php" method="post" enctype="multipart/form-data">
Username: <input type="text" name="username" /><br>
Password: <input type="password" name="password" /><br>
Full Name: <input type="text" name="full_name" /><br>
Email Address: <input type="text" name="email" /> <br>
Phone Number: <input type="text" name="phone" /> (optional)<br>
Address: <input type="text" name="address" /> (optional)<br>
About me: <input type="text" name="about_me" /> (optional)<br>
Enter the code below: <input type="text" name="captcha" /><br>
<?php
session_start();
include("simple-php-captcha-master/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
?>
<br>
<input type="submit" value="Register"/>
</form>

<?php
// Turn off all error reporting
error_reporting(0);

include("connect.php");

  //check if username registered already exist
  function usernameExist($sqlConnect){
    $sqlQuery = "SELECT u.username from  USER u";
    $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
    $username_exist = false;
    while($sqlRow = mysqli_fetch_array($sqlResult)){
      $us= $sqlRow['username'];
      if($us == $_POST['username']){
        $username_exist = true;
      }
    }
    return $username_exist;
  }

  if($_POST['email']==NULL ||$_POST['username']==NULL ||$_POST['password']==NULL ||$_POST['full_name']==NULL){
    echo "all fields must be filled";
  }
  else if ($_SESSION['captcha_user_input']['code'] != $_POST['captcha']) {
    echo "wrong captcha";
  }
  else if(usernameExist($sqlConnect)){
    echo "username already used, please try another one";
  }
  else{
      $email = $_POST['email'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $full_name = $_POST['full_name'];
      $phone = $_POST['phone'];
      $address = $_POST['address'];
      $about_me = $_POST['about_me'];
      $latitude = 0;
      $longitude = 0;
      $insertSql = "INSERT INTO USER (username, password, email, full_name, phone, address, about_me, latitude, longitude, chat_access)
                                values('".$username."', '".$password."', '".$email."','".$full_name."', '".$phone."',
                                '".$address."', '".$about_me."', '".$latitude."', '".$longitude."', SYSDATE())";
      mysqli_query($sqlConnect, $insertSql);
      echo "success register, ".$username.", ".$password.", ".$full_name.", ".$email.", ".$phone;

      //email dont work locally
      mail("gabrielbaky@gmail.com","Test","test");

      $_SESSION['username'] = $username;

      header("Location: get_location.php");
      die();
    }

    $_SESSION['captcha_user_input'] = $_SESSION['captcha'];

    mysqli_close($sqlConnect);

?>
<br><br>
<a href="login.php">Login</a>
</body>
</html>
