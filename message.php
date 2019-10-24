<html>
<head>
  <meta charset="utf-8">
  <title> Streetrades </title>
  <link rel="stylesheet" type="text/css" href="normalize.css">
  <link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  <div class="header">
      <img src="images/flockmart.png" alt="FlockMart"/>
    </div>
    <div class="register">
      <a href="home.php">| Home |</a>
      <a href="profile.php">| My Profile |</a>
      <a href="post.php">| Sell Item |</a>
      <a href="message.php">| Chat |</a>
      <a href="logout.php">| Logout |</a><br>
      <p id="header-left">CHAT</p>
<?php
// Turn off all error reporting
error_reporting(0);

include("check_session.php");

include("connect.php");

include("functions.php");


if($_GET['seller']!=NULL){//START IF SELLER NOT NULL
  //msging header
  echo  "<a href='seller.php?username=".$_GET['seller']."'>".$_GET['seller']."</a></p>";

  //insert from $_POST to db
  if($_POST['message']!=NULL && str_replace(' ', '', $_POST['message'])!=""){
    $insertSql = "INSERT INTO MESSAGE (sender, reciever, content, status)
                              values('".$_SESSION['username']."', '".$_GET['seller']."', '".$_POST['message']."', 'unread')";
    mysqli_query($sqlConnect, $insertSql);
  }

  //fetch from db
  $sqlQuery = "SELECT m.msg_id, m.sender, m.reciever, m.content, m.status from  MESSAGE m";
  $sqlResult = mysqli_query($sqlConnect, $sqlQuery);

  //collect all chat in db into an array
  $status;
  $chatArray = array();
  while($sqlRow = mysqli_fetch_array($sqlResult)){
    if( ($sqlRow['sender']==$_SESSION['username']&&$sqlRow['reciever']==$_GET['seller']) ||
     ($sqlRow['reciever']==$_SESSION['username']&&$sqlRow['sender']==$_GET['seller']) ){
      //echo $sqlRow['sender']." : ".$sqlRow['content']."<br>";
      //if send from sender, change status to 2
      if($sqlRow['reciever']==$_SESSION['username']&&$sqlRow['sender']==$_GET['seller']){
        //case we recieve they send
        $sqlUpdate = "UPDATE MESSAGE SET status= 'read' WHERE sender='".$_GET['seller']."'";
        mysqli_query($sqlConnect, $sqlUpdate);
        //$status = $sqlRow['status'];
      } else {
        //case we send they recieve
        $status = $sqlRow['status'];
      }
      //$status = $sqlRow['status'];//may remove this bcs sqlRow is not UPDATE yet here, so replace w function
      $chatText = $sqlRow['sender']." : ".$sqlRow['content']."<br>";
      array_push($chatArray, $chatText);
    }
  }

  //reverse sort the array
  $reverse = array_reverse($chatArray);

  //add in last 21 chats into an array
  $chatArrayToDisplay = array();
  $chatCount = 0;
  while($chatCount<31 && $chatCount<sizeof($reverse)){
    array_push($chatArrayToDisplay, $reverse[$chatCount]);
    $chatCount = $chatCount + 1;
  }

  //reverse chat array to original order
  $reverseBack = array_reverse($chatArrayToDisplay);

  //print chat array
  print_r($reverseBack);

   ?>
   <form action="message.php?seller=<?php echo $_GET['seller'];?>" method="post" enctype="multipart/form-data">
  <input type="text" name="message" />
  <input type="submit" value="Send"/>
  </form>
  Status: <?php echo $status; ?>
  <br>
  <a href='message.php?seller=<?php echo $_GET['seller'];?>'>Refresh</a>
  <br><br><br>
  <?php

  //if sender is get seller and reciever is session username, change status to 2(read)
  //readMessage($sqlConnect, $_GET['seller'], $_SESSION['username']);

}//END OF IF SELLER NOT NULL


//MESSAGE LIST
$message_list = array();

//fetch messages from db
$sqlQuery = "SELECT * FROM MESSAGE  ORDER BY msg_id DESC";
$sqlResult = mysqli_query($sqlConnect, $sqlQuery);
while($sqlRow = mysqli_fetch_array($sqlResult)){
  if($sqlRow['sender']==$_SESSION['username'] && !in_array($sqlRow['reciever'], $message_list)){
      echo "<a href='message.php?seller=".$sqlRow['reciever']."'>".$sqlRow['reciever']."</a><br>";
      echo $sqlRow['content']."<br>";
      echo $sqlRow['last_access']."<br><br><br>";
      array_push($message_list, $sqlRow['reciever']);
 } else if($sqlRow['reciever']==$_SESSION['username'] && !in_array($sqlRow['sender'], $message_list)){
      echo "<a href='message.php?seller=".$sqlRow['sender']."'>".$sqlRow['sender']."</a><br>";
      echo $sqlRow['content']."<br>";
      echo $sqlRow['last_access']."<br><br><br>";
      array_push($message_list, $sqlRow['sender']);
 }
}



 mysqli_close($sqlConnect);
 ?>
 </div>
 </body>
 </html>
