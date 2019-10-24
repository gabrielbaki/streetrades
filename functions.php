<?php

//check if username and password combination exist
function usernameAndPasswordExist($username, $password, $sqlConnect){
  //initialize boolean
  $usernameAndPasswordExist = false;
  //check talent table
  $sqlQuery = "SELECT t.username, t.password from USER t";
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    $us= $sqlRow['username'];
    $pass = $sqlRow['password'];
    if($username == $us && $password == $pass){
      $usernameAndPasswordExist = true;
    }
  }
  //return boolean
  return $usernameAndPasswordExist;
}

/**
 * Calculates the great-circle distance between two points, with
 * the Vincenty formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 * note: bcs earth rad in km is 6371, if input that to 4th param, result will be in km
 */
function vincentyGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $lonDelta = $lonTo - $lonFrom;
  $a = pow(cos($latTo) * sin($lonDelta), 2) +
    pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
  $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

  $angle = atan2(sqrt($a), $b);
  return $angle * $earthRadius;
}

//phytagoren theorm to get distance from2 pairs of lat and long
function geoLocPhytagoreanDistance($latFrom, $longFrom, $latTo, $longTo){
  $lat_del = $latFrom-$latTo;
  $long_del = $longFrom-$longTo;
  $distance = sqrt(pow($lat_del, 2)+pow($long_del, 2));
  $distance_detail = number_format((float)$distance, 7, '.', '');
  return $distance_detail;
}

//image validation proccess
function validateImage($image){
  if(isset($_FILES['image'])){
    $error = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
    $expension =array("jpeg", "jpg", "png");
  if (in_array($file_ext, $expension)==false){
    $errors[]="file/image extension not allowed, use jpeg, jpg, png";
  }
    if(empty($errors)==false){
      //move_uploaded_file($file_tmp, "images/".$file_name);//move image file to /latihan/images, need Mac permission to work
      print_r($errors);
    }
  }
}

//check if there is new chat NOT USED ANYMORE
function newUnreadChat($sqlConnect){
  //get last chat access by user
  $sqlQuery = "SELECT u.username, u.chat_access from  USER u";;
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    $us= $sqlRow['username'];
    if($us == $_SESSION['username'] ){
      $chatAccess = date_parse($sqlRow['chat_access']);
    }
  }

  //fetch messages from db
  $unread=false;
  $sqlQuery = "SELECT * FROM MESSAGE  ORDER BY last_access DESC";
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    if($sqlRow['reciever']==$_SESSION['username']){
      if(date_parse($sqlRow['last_access'])>$chatAccess){
        $unread=true;
      } else {
        $unread=false;
      }
      break;
    }
  }

  return $unread;

}

//check if input exist in post title or desc
//function searchExist($input, $title, $description){
  //$total = " ".$title." ".$description;
  //break down $input into words array if there is space then loop
  //$result = strpos($total, $input);
  //$bool;
  //if($result == false){
    //$bool = false;
  //} else {
    //$bool =  true;
  //}
  //return $bool;
//}

//check if input exist in post title or desc
function searchExist($input, $title, $description){
  $total = " ".$title." ".$description;
  $word_list = array();
  $word_list = explode(" ", $input);
  $count = 0;
  $bool = false;
  while($count<sizeof($word_list)){
    $result = strpos(strtolower($total), strtolower($word_list[$count]));
    if($result == true){
      $bool = true;
    }
    $count = $count + 1;
  }
  return $bool;
}

//check weather or not a specific person
//sent message user have not read
function recieveUnreadSender($sender, $sqlConnect){
  $bool=false;
  //fetch from db
  $sqlQuery = "SELECT m.msg_id, m.sender, m.reciever, m.content, m.status from  MESSAGE m";
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
  //run trough
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    if($sqlRow['reciever']==$_SESSION['username'] && $sqlRow['sender']==$sender
    && $sqlRow['status']=='unread'){
      $bool = true;
    }
  }
  return $bool;
}

//notif if recieve anything unread from any sender
function recieveUnreadAny($sqlConnect){
  $bool=false;
  //fetch from db
  $sqlQuery = "SELECT m.msg_id, m.sender, m.reciever, m.content, m.status from  MESSAGE m";
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);
  //run trough
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    if($sqlRow['reciever']==$_SESSION['username'] && $sqlRow['status']=='unread'){
      $bool = true;
    }
  }
  return $bool;
}

function compressImage($image_tmp){
  $ext = pathinfo($post_photo, PATHINFO_EXTENSION);  // getting image extension

	if(   $ext=='png' || $ext=='PNG' ||
	$ext=='jpg' || $ext=='jpeg' ||
	$ext=='JPG' || $ext=='JPEG' ||
	$ext=='gif' || $ext=='GIF'  )  // checking image extension
{
	if($ext=='jpg' || $ext=='jpeg' || $ext=='JPG' || $ext=='JPEG')
	{
	$src=imagecreatefromjpeg($post_photo_tmp);
	}
	if($ext=='png' || $ext=='PNG')
	{
	$src=imagecreatefrompng($post_photo_tmp);
	}
		if($ext=='gif' || $ext=='GIF')
	{
	$src=imagecreatefromgif($post_photo_tmp);
	}

  list($width_min,$height_min)=getimagesize($image_tmp); // fetching original image width and height
	$newwidth_min=350; // set compressing image width
	$newheight_min=($height_min / $width_min) * $newwidth_min; // equation for compressed image height
	$tmp_min = imagecreatetruecolor($newwidth_min, $newheight_min); // create frame  for compress image
	imagecopyresampled($tmp_min, $src, 0,0,0,0,$newwidth_min, $newheight_min, $width_min, $height_min); // compressing image
  }
}

function compressImage2($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}

?>
