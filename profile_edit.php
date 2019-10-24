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
  <p id="header-title">EDIT PROFILE</p>
<?php
include("check_session.php");

include("connect.php");

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

<form action="profile_update.php" method="post" enctype="multipart/form-data">
Username: <?php echo $get_username;?> <br>
Full Name: <input type="text" name="full_name" value= "<?php echo $get_full_name;?>"/><br>
Email Address: <input type="text" name="email" value= "<?php echo $get_email;?>" /><br>
Phone Number: <input type="text" name="phone" value= "<?php echo $get_phone;?>"/><br>
Address: <input type="text" name="address" value= "<?php echo $get_address;?>"/><br>
About me: <input type="text" name="about_me" value= "<?php echo $get_about_me;?>"/><br>
<input type="submit" value="Update"/>
</form>

<?php
mysqli_close($sqlConnect);
?>
</div>
</body>
</html>
