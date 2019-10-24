<?php
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
  <a href="message.php">| Chat |</a><br>
  <a href="logout.php">| Logout |</a><br>
  <p id="header-title">SELLER</p>
  <div style="display: inline-block; text-align: left">
<?php
//populate get data of logged in user
$sqlQuery = "SELECT u.username, u.full_name, u.email, u.phone, u.address, u.about_me from  USER u";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $us= $sqlRow['username'];
  if($us == $_GET['username'] ){
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
<a href='message.php?seller=<?php echo $get_username;?>'>Chat</a>

<?php
$sqlQueryPost = "SELECT u.post_id, u.username, u.title, u.description, u.price, u.category, u.image from  POST u";
$sqlResultPost = mysqli_query($sqlConnect, $sqlQueryPost);
while($sqlRowPost = mysqli_fetch_array($sqlResultPost)){
  $usPost= $sqlRowPost['username'];
  if($usPost == $_GET['username'] ){
    echo "<details>";
    echo '<summary><img height="200" width="200" src="data:image;base64,'.$sqlRowPost['image'].'"/></summary>';
    echo $sqlRowPost['title']."<br> ".$sqlRowPost['description']."<br> US$".$sqlRowPost['price']."<br>  ".$sqlRowPost['category'];
    echo "</details><br>";
  }
}

mysqli_close($sqlConnect);
?>
</div>
</div>
</body>
</html>
