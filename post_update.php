<?php

include("check_session.php");

include("connect.php");

//if image attachment exist & attachment is jpeg/jpg/png, update
$file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
$expension =array("jpeg", "jpg", "png");
if($_FILES['image']['tmp_name']!=NULL && in_array($file_ext, $expension)!=false){
  $new_image = base64_encode(file_get_contents(addslashes($_FILES['image']['tmp_name'])));
  $sqlUpdate = "UPDATE POST SET image='".$new_image."' WHERE post_id='".$_GET['id']."'";
  mysqli_query($sqlConnect, $sqlUpdate);
}

//update data from form
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$about_me= $_POST['about_me'];
$sqlUpdate = "UPDATE POST SET title='".$_POST['title']."', category='".$_POST['category']."', description='".$_POST['description']."', price='".$_POST['price']."'
  WHERE post_id='".$_GET['id']."'";

if (mysqli_query($sqlConnect, $sqlUpdate)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($sqlConnect);
}


//redirect to profile update page
header("Location: profile.php");
die();
 ?>
