<?php
include("check_session.php");

include("connect.php");

// Turn off all error reporting
error_reporting(0);

$lat = $_GET['lat'];
$long = $_GET['long'];

echo 'fetching location.. <br>
please allow web browser to acess your location <br>
or <a href="get_location.php">refresh page</a>';

if($lat!=NULL && $long!=NULL){
  $sqlUpdate1 = "UPDATE USER SET latitude='".$lat."', longitude='".$long."' WHERE username='".$_SESSION['username']."'";
  mysqli_query($sqlConnect, $sqlUpdate1);
  //update all post with username
  $sqlUpdate2 = "UPDATE POST SET latitude='".$lat."', longitude='".$long."' WHERE username='".$_SESSION['username']."'";
  mysqli_query($sqlConnect, $sqlUpdate2);
  header("Location: home.php");
  die();
}

 ?>

<script>
var x = document.getElementById("demo");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);

    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
  //bikin variable utk ngisi koordinat ke peta
  //  var latlon = position.coords.latitude + "," + position.coords.longitude;
//panggil api google
    //var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="
    //+latlon+"&zoom=14&size=400x300&sensor=false";
    //show di peta
    //document.getElementById("mapholder").innerHTML = "<img src='"+img_url+"'>";
    //show angka long lat
	//x.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;

  //get in php
  var lat = position.coords.latitude;
  var long = position.coords.longitude;
  //redirect to this url, then use GET to get data
  window.location.href = "get_location.php?lat=" + lat + "&long=" + long;
}

//error handling
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}

//initialize execution
getLocation();

</script>
