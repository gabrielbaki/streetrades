<?php
// Turn off all error reporting
error_reporting(0);

include("check_session.php");

include("connect.php");

include("functions.php");

?>
<!DOCTYPE html>
<html>
<title>FlockMart</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
.w3-sidenav a,.w3-sidenav h4 {font-weight:bold}
</style>
<body class="w3-light-grey w3-content" style="max-width:1600px">

<!-- Sidenav/menu -->
<nav class="w3-sidenav w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidenav"><br>
  <div class="w3-container">
    <a href="#" onclick="w3_close()" class="w3-hide-large w3-right w3-jumbo w3-padding" title="close menu">
      <i class="fa fa-remove"></i>
    </a>
    <img src="flockmart.png" style="width:45%;" class="w3-round"><br><br>
  </div>
  <a href="profile.php"> Welcome, <?php echo $_SESSION['username']; ?></a><br>
  <a href="#" class="w3-padding w3-text-teal">Home</a><br>
  <a href="post.php">Sell Item</a><br>
  <a href="message_list.php">Chat</a><br>
  <a href="about_us.php">About Us & Contact</a><br>
  <a href="privacy_policy.php">Terms of use & Privacy Policy</a><br>
  <a href="logout.php">Logout</a><br>

  <div class="w3-section w3-padding-top w3-large">
    <a href="#" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"><i class="fa fa-facebook-official"></i></a>
    <a href="#" class="w3-hover-white w3-hover-text-red w3-show-inline-block"><i class="fa fa-pinterest-p"></i></a>
    <a href="#" class="w3-hover-white w3-hover-text-light-blue w3-show-inline-block"><i class="fa fa-twitter"></i></a>
    <a href="#" class="w3-hover-white w3-hover-text-grey w3-show-inline-block"><i class="fa fa-flickr"></i></a>
    <a href="#" class="w3-hover-white w3-hover-text-indigo w3-show-inline-block"><i class="fa fa-linkedin"></i></a>
  </div>
</nav>

<!-- Overlay effect when opening sidenav on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px">

  <!-- Header -->
  <header class="w3-container">
    <a href="#"><img src="img_avatar_g2.jpg" style="width:65px;" class="w3-circle w3-right w3-margin w3-hide-large w3-hover-opacity"></a>
    <span class="w3-opennav w3-hide-large w3-xxlarge w3-hover-text-grey" onclick="w3_open()"><i class="fa fa-bars"></i></span>
    <div class="header">
      <img src="images/flockmart_grey.png" alt="FLockMart" border-width="1px" border-color="Black"/>
    </div>
    <div class="w3-section w3-bottombar w3-padding-16">
      <form action="homeW3.php" method="get" enctype="multipart/form-data">
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
    </div>
  </header>

  <!-- First Photo Grid-->
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
    $posts[$i] = array("image"=>$sqlRow['image'] , "title"=>$sqlRow['title'], "category"=>$sqlRow['category'], "description"=>$sqlRow['description'],
    "price"=>"US$".$sqlRow['price'], "distance"=>$distance, "username"=>$sqlRow['username']);
        //echo $posts[$i]["distance"];
        //echo $lat_del." & ".$long_del." || ";
        //echo $lat." || ".$long." || ".$sqlRow['latitude']." || ".$sqlRow['longitude']."  ==||==  ";
    //foreach ($posts[$i] as $key => $value) {
      //echo "Key: ".$key.", Value: ".$value.".  ";
    //}
    $i++;
  }

  //sort by distance
  usort($posts, function($a, $b) {
      return $a["distance"] <=> $b["distance"];
  });
  //print
  $k=0;
  while($k<$i){
    //dont show your own post
    if($posts[$k]["username"]!=$_SESSION['username']
    //category filter
    && ($posts[$k]["category"]==$_GET['category'] || $_GET['category']=="All" || $_GET['category']==NULL)
    //search filter
    && ($_GET['search']==NULL || str_replace(' ', '', $_GET['search'])=="" || $posts[$k]["title"]==$_GET['search'] ||
    $posts[$k]["description"]==$_POST['search'])
    ){
        foreach ($posts[$k] as $key => $value) {
          if($key!="image"){
            if($key!="distance"){
              if($key!="username"){
                echo "<p>".$key." : ".$value."</p>";
              } else {
                echo "<p>seller : <a href='seller.php?username=".$value."'>".$value."</a></p>";
                echo "<a href='message.php?seller=".$value."'>Chat</a> with ".$value."</p>";
              }
            } else {
              echo "<p>".$key." : ".round($value, 3)."km</p>";
            }
          } else {
            echo '<div class="w3-row-padding">
            <div class="w3-third w3-container w3-margin-bottom">
            <img alt="Norway" height="247" width="247" class="w3-hover-opacity" src="data:image;base64,'.$value.'"/>
              <div class="w3-container w3-white">
            <details>';
          }
        }
        echo "</details> <br>";
      echo "</div>
    </div>
  </div>";
  }
$k++;
}
mysqli_close($sqlConnect);
  ?>

  <!-- Second Photo Grid-->
  <div class="w3-row-padding">
    <div class="w3-third w3-container w3-margin-bottom">
      <img src="img_fjords.jpg" alt="Norway" style="width:100%" class="w3-hover-opacity">
      <div class="w3-container w3-white">
        <p><b>Lorem Ipsum</b></p>
        <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
      </div>
    </div>
    <div class="w3-third w3-container w3-margin-bottom">
      <img src="img_lights.jpg" alt="Norway" style="width:100%" class="w3-hover-opacity">
      <div class="w3-container w3-white">
        <p><b>Lorem Ipsum</b></p>
        <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
      </div>
    </div>
    <div class="w3-third w3-container">
      <img src="img_mountains.jpg" alt="Norway" style="width:100%" class="w3-hover-opacity">
      <div class="w3-container w3-white">
        <p><b>Lorem Ipsum</b></p>
        <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
      </div>
    </div>
  </div>

  <!-- Pagination -->
  <div class="w3-center w3-padding-32">
    <ul class="w3-pagination">
      <li><a class="w3-black" href="#">1</a></li>
      <li><a class="w3-hover-black" href="#">2</a></li>
      <li><a class="w3-hover-black" href="#">3</a></li>
      <li><a class="w3-hover-black" href="#">4</a></li>
      <li><a class="w3-hover-black" href="#">»</a></li>
    </ul>
  </div>

  <!-- Footer -->
  <footer class="w3-container w3-padding-32 w3-white">
  <div class="w3-row-padding">
    <div class="w3-third">
      <h3>FOOTER</h3>
      <p>Praesent tincidunt sed tellus ut rutrum. Sed vitae justo condimentum, porta lectus vitae, ultricies congue gravida diam non fringilla.</p>
      <p>Powered by <a href="http://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
    </div>

    <div class="w3-third">
      <h3>BLOG POSTS</h3>
      <ul class="w3-ul w3-hoverable">
        <li class="w3-padding-16">
          <img src="img_workshop.jpg" class="w3-left w3-margin-right" style="width:50px">
          <span class="w3-large">Lorem</span><br>
          <span>Sed mattis nunc</span>
        </li>
        <li class="w3-padding-16">
          <img src="img_gondol.jpg" class="w3-left w3-margin-right" style="width:50px">
          <span class="w3-large">Ipsum</span><br>
          <span>Praes tinci sed</span>
        </li>
      </ul>
    </div>

    <div class="w3-third">
      <h3>POPULAR TAGS</h3>
      <p>
        <span class="w3-tag w3-black w3-margin-bottom">Travel</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">New York</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">London</span>
        <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">IKEA</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">NORWAY</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">DIY</span>
        <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Ideas</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Baby</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Family</span>
        <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">News</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Clothing</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Shopping</span>
        <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Sports</span> <span class="w3-tag w3-dark-grey w3-small w3-margin-bottom">Games</span>
      </p>
    </div>

  </div>
  </footer>

<!-- End page content -->
</div>

<script>
// Script to open and close sidenav
function w3_open() {
    document.getElementById("mySidenav").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidenav").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}
</script>

</body>
</html>
