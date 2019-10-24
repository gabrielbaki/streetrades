<?php
include("functions.php");
include("check_session.php");
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
  <a href="logout.php">| Logout |</a><br>
  <p id="header-title">MY PROFILE</p>
<div style="display: inline-block; text-align: left">
<?php

// Turn off all error reporting
error_reporting(0);

//populate get data of logged in user
$sqlQuery = "SELECT u.username, u.full_name, u.email, u.phone, u.address, u.about_me from  USER u";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $us= $sqlRow['username'];
  if($us == $_SESSION['username'] ){
    $get_username = $sqlRow['username'];
    $get_email = $sqlRow['email'];
    $get_full_name = $sqlRow['full_name'];
    $get_phone = $sqlRow['phone'];
    $get_address = $sqlRow['address'];
    $get_about_me = $sqlRow['about_me'];
  }
}

?>


Username: <?php echo $get_username;?> <br>
Full Name: <?php echo $get_full_name;?> <br>
Email Address: <?php echo $get_email;?> <br>
Phone Number: <?php echo $get_phone;?> <br>
Address: <?php echo $get_address;?> <br>
About me: <?php echo $get_about_me;?> <br>
<br>
<a href="profile_edit.php">Edit Profile</a><br>
<a href="change_password.php">Change Password</a><br>
<br>
<?php
//get data of posts of logged in users
$sqlQueryPost = "SELECT u.post_id, u.username, u.title, u.description, u.price, u.category, u.image from  POST u";
$sqlResultPost = mysqli_query($sqlConnect, $sqlQueryPost);
while($sqlRowPost = mysqli_fetch_array($sqlResultPost)){
  $usPost= $sqlRowPost['username'];
  if($usPost == $_SESSION['username'] ){
    echo '<table style=" width="200" align="center" cellspacing="30" "><tbody>';
    echo "<details>";
    echo '<summary><img height="300" width="300" src="data:image;base64,'.$sqlRowPost['image'].'"/>';
    echo '<br><strong>'.$sqlRowPost['title'].'</strong></summary>';
  //  echo '<div style" align="left" width:"600px" ">';
    echo "<br>category : ".$sqlRowPost['category']."<br>price : US$".$sqlRowPost['price']."<br>description : ".$sqlRowPost['description'];
    echo "<br><a href='post_edit.php?id=".$sqlRowPost['post_id']."'>edit</a> ";
    echo "<br><a href='post_delete.php?id=".$sqlRowPost['post_id']."'>delete</a> <br></div></table>";
    echo "</tbody></table></details><br>";
  }
}

mysqli_close($sqlConnect);
?>
</div>
</div>
</body>
</html>
