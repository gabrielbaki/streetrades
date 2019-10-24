<?php

include("check_session.php");

include("connect.php");

//update data from form
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$about_me= $_POST['about_me'];
$sqlUpdate = "UPDATE USER SET full_name='".$full_name."', email='".$email."', phone='".$phone."', address='".$address."', about_me='".$about_me."'
  WHERE username='".$_SESSION['username']."'";
if (mysqli_query($sqlConnect, $sqlUpdate)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($sqlConnect);
}


//redirect to profile update page
header("Location: profile.php");
die();
 ?>
