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
      <img src="images/flockmart.png"/>
  </div>
<div class="login">

<a href="home.php">| Home |</a>
<a href="profile.php">| My Profile |</a>
<a href="post.php">| Sell Item |</a>
<a href="message.php">| Chat |</a>
<a href="logout.php">| Logout |</a>

<form action="home.php" method="get" enctype="multipart/form-data">
Search: <input type="text" name="search" />
Category: <select name="category">
        <option>All</option>
        <option>Books/Education</option>
        <option>Furniture/Home Applience</option>
        <option>Electronic</option>
        <option>Service</option>
        <option>Clothing/Fashion</option>
        <option>Food/Drinks/Consumption</option>
        <option>Apartment/Housing</option>
        <option>Cars/Motors</option>
        <option>Others</option>
        </select>
<input type="submit" value="Search"/>
</form>
<?php
//get lat long of logged user
$sqlQuery = "SELECT u.username, u.latitude, u.longitude from  USER u";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $us= $sqlRow['username'];
  if($us == $_SESSION['username'] ){
    $lat = $sqlRow['latitude'];
    $long = $sqlRow['longitude'];
  }
}

//create array of post data + distance to logged user
$posts = array();
$i=0;
$sqlQuery = "SELECT u.username, u.title, u.description, u.price, u.category, u.latitude, u.longitude, u.image from  POST u";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  $distance = vincentyGreatCircleDistance(number_format((float)$lat, 7, '.', ''), number_format((float)$long, 7, '.', ''),
  number_format((float)$sqlRow['latitude'], 7, '.', ''), number_format((float)$sqlRow['longitude'], 7, '.', ''), 6371);
  $posts[$i] = array("image"=>$sqlRow['image'] , "title"=>$sqlRow['title'], "distance"=>$distance, "category"=>$sqlRow['category'],
  "price"=>"US$".$sqlRow['price'], "description"=>$sqlRow['description'], "username"=>$sqlRow['username']);
      //echo $posts[$i]["distance"];
      //echo $lat_del." & ".$long_del." || ";
      //echo $lat." || ".$long." || ".$sqlRow['latitude']." || ".$sqlRow['longitude']."  ==||==  ";
  //foreach ($posts[$i] as $key => $value) {
    //echo "Key: ".$key.", Value: ".$value.".  ";
  //}
  $i++;
}

//sort function
function my_sort($a,$b)
{
  if ($a==$b) return 0;
  return ($a["distance"]<$b["distance"])?-1:1;
}

//sort the array
usort($posts,"my_sort");

//print
$k=0; //track loop
$j=0; //track what get into if()
echo '<table style=" width="200" align="center" cellspacing="30" "><tbody><tr>';
while($k<$i){
  //dont show your own post
  if(($posts[$k]["username"]!=$_SESSION['username'])
  //filter distance
  && ($distance<30.001)
  //category filter
  && ($posts[$k]["category"]==$_GET['category'] || $_GET['category']=="All" || $_GET['category']==NULL)
  //search filter
  && ($_GET['search']==NULL || str_replace(' ', '', $_GET['search'])=="" ||
  searchExist($_GET['search'], $posts[$k]["title"], $posts[$k]["description"]))
  //$posts[$k]["title"]==$_GET['search'] ||
  //$posts[$k]["description"]==$_POST['search'])
  ){
    echo '<td><details>';
    foreach ($posts[$k] as $key => $value) {
      if($key!="image"){
        if($key!="distance"){
          if($key!="username"){
            if($key!="title"){
              echo $key." : ".$value."<br>";
            } else{
              echo "<br><strong>".$value."</strong></summary>";
              echo '<div style" align="left" ">';
            }
          } else {
            echo "seller : <a href='seller.php?username=".$value."'>".$value."</a><br>";
            echo "<a href='message.php?seller=".$value."'>Chat</a> with ".$value."</p>";
            echo "</div>";
          }
        } else {
          echo $key." : ".round($value, 3)."km away <br>";
        }
      } else {
        echo "<summary>".
        '<img height="250" width="250" src="data:image;base64,'.$value.'"/>';
        //."</summary>";
      }
    }
    echo "</details></td> <br>";
    $j++;
  }
  if($j%4==0){ //number of item in 1 row
    echo "</tr><tr>";
  }
  $k++;
}
echo '</tr></tbody></table>';

mysqli_close($sqlConnect);

 ?>


 <br>
 <footer>
   <a href="about_us.php">About Us & Contact</a><br><br>
   <a href="privacy_policy.php">Terms of use & Privacy Policy</a><br><br>
 </footer>


 </div>
 </body>
 </html>
