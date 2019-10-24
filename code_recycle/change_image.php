<?php
include("check_session.php");

include("connect.php");

if($_FILES['image']['tmp_name']!=NULL){
  $new_image = base64_encode(file_get_contents(addslashes($_FILES['image']['tmp_name'])));
    $sqlUpdate = "UPDATE POST SET image='".$new_image."' WHERE post_id='".$_GET['id']."'";
    mysqli_query($sqlConnect, $sqlUpdate);
}

header("Location: profile.php");
die();

mysqli_close($sqlConnect);

 ?>
