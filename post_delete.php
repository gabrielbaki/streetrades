<?php
include("check_session.php");
include("connect.php");
$sqlDelete = "DELETE FROM POST WHERE post_id=".$_GET['id'];
mysqli_query($sqlConnect, $sqlDelete);
mysqli_close($sqlConnect);
header("Location: profile.php");
die();
?>
