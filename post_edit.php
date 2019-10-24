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
<p id="header-title">EDIT ITEM</p>
<?php
include("check_session.php");

include("connect.php");

//populate get data of logged in user
$sqlQuery = "SELECT u.post_id, u.username, u.title, u.description, u.price, u.category, u.image from  POST u";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);

while($sqlRow = mysqli_fetch_array($sqlResult)){
  $id= $sqlRow['post_id'];
  if($id == $_GET['id'] ){
    $get_username = $sqlRow['username'];
    $get_title = $sqlRow['title'];
    $get_description = $sqlRow['description'];
    $get_price = $sqlRow['price'];
    $get_category = $sqlRow['category'];
    $get_image = $sqlRow['image'];
  }
}

?>
<form action="post_update.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
  <img height="200" width="200" src="data:image;base64, <?php echo $get_image?>"/><br>
  <input type="file" name="image" /><br>
Username: <?php echo $get_username;?> <br>
Title: <input type="text" name="title" value= "<?php echo $get_title;?>"/><br>
Category: <select name="category" value="<?php echo $get_category;?>">
        <option>Books/Education</option>
        <option>Furniture/Home Applience</option>
        <option>Electronic</option>
        <option>Service</option>
        <option>Clothing/Fashion</option>
        <option>Food/Drinks/Consumption</option>
        <option>Apartment/Housing</option>
        <option>Cars/Motors</option>
        <option>Others</option>
        </select> <br>
Description: <input type="text" name="description" value= "<?php echo $get_description;?>"/><br>
Price: US$<input type="text" name="price" value= "<?php echo $get_price;?>"/><br>
<input type="submit" value="Update"/>
</form>

<?php
mysqli_close($sqlConnect);
?>
</div>
</body>
</html>
