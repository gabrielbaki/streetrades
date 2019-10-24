<?php
include("check_session.php");

include("connect.php");

$message_list = array();

//fetch from db
$sqlQuery = "SELECT * FROM MESSAGE  ORDER BY last_access DESC";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  if($sqlRow['sender']==$_SESSION['username'] && !in_array($sqlRow['reciever'], $message_list)){
    echo "<a href='message.php?seller=".$sqlRow['reciever']."'>".$sqlRow['reciever']."</a><br>";
    echo $sqlRow['content']."<br>";
    echo $sqlRow['last_access']."<br><br><br>";
    array_push($message_list, $sqlRow['reciever']);
 } else if($sqlRow['reciever']==$_SESSION['username'] && !in_array($sqlRow['sender'], $message_list)){
    echo "<a href='message.php?seller=".$sqlRow['reciever']."'>".$sqlRow['reciever']."</a><br>";
    echo $sqlRow['content']."<br>";
    echo $sqlRow['last_access']."<br><br><br>";
    array_push($message_list, $sqlRow['seller']);
 }
}
?>
