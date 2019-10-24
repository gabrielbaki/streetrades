<?php
// Turn off all error reporting
error_reporting(0);

include("check_session.php");

include("connect.php");

include("functions.php");
?>
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
      <a href="home.php">| Home |</a>
      <a href="profile.php">| My Profile |</a>
      <a href="post.php">| Sell Item |</a>
      <a href="message.php">| Chat |</a>
      <a href="logout.php">| Logout |</a><br>
  <p id="header-title">Sell Item</p><br>
  <div style="display: inline-block; text-align: left">
<form action="post.php" method="post" enctype="multipart/form-data">
Image:  <input type="file" name="image" /> <br>(use .jpeg, .jpg, .png)<br>
Title: <input type="text" name="title" /><br>
Category: <select name="category">
        <option>Books</option>
        <option>Furniture/Home Appliances</option>
        <option>Electronic</option>
        <option>Service</option>
        <option>Clothing/Fashion</option>
        <option>Food/Drinks/Consumption</option>
        <option>Apartment/Housing</option>
        <option>Cars/Motors</option>
        <option>Others</option>
        </select> <br>
Description: <input type="text" name="description" /><br>
Price: US$ <input type="number" name="price" /><br>
Enter the code below: <input type="text" name="captcha" /><br><br>
<?php
session_start();
include("simple-php-captcha-master/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
?>
<br>
<input type="submit" value="Post"/>
</form>
<?php
  //image fila validation
  if(isset($_FILES['image'])){
    $error = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
    $expension =array("jpeg", "jpg", "png", "gif");

  if (in_array($file_ext, $expension)==false){
    $errors[]="file/image extension not allowed, use jpeg, jpg, png <br>";
  } else {
    //compress($_FILES['image']['tmp_name'], "/images", 75);
   }
    if(empty($errors)==false){
      echo $errors[0];
    }
  }

//compress image
//ob_start();
//compressImage2($_FILES['image']['tmp_name'], NULL, 10);
//$imageData = ob_get_clean();

//post conditions
if($_FILES['image']['tmp_name']==NULL || $_POST['title']==NULL ||$_POST['description']==NULL
||$_POST['price']==NULL ||$_POST['category']==NULL ||$_POST['captcha']==NULL){
  echo "all fields must be filled";
}
else if ($_SESSION['captcha_user_input']['code'] != $_POST['captcha']) {
  echo "wrong captcha";
}
else{
  $image = base64_encode(file_get_contents(addslashes($_FILES['image']['tmp_name'])));
  //$image = base64_encode(file_get_contents(addslashes($imageData)));
  $title = $_POST['title'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $category = $_POST['category'];
  $insertSql = "INSERT INTO POST (image, username, title, description, price, category, latitude, longitude)
                            values('".$image."', '".$_SESSION['username']."', '".$title."', '".$description."',
                            '".$price."', '".$category."', 0, 0)";
  mysqli_query($sqlConnect, $insertSql);
  //echo "success post, ".$title.", ".$category.", ".$description.", ".$price;

  header("Location: get_location.php");
  die();
  }

$_SESSION['captcha_user_input'] = $_SESSION['captcha'];

mysqli_close($sqlConnect);

?>
</div>
</div>
</body>
</html>
