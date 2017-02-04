<?php
session_start();
?>

<?php

if(!($_SESSION['login_check']))
{
	header("location: login.php");
die;
	}
?>
<html>
<head><title>Message Board</title></head>
<body>
<form method="POST" action="board.php" id="111">
<textarea rows="4" cols="50" name="message" form ="111"> </textarea>
<br></br>
<input type="submit" name="submit_message" value="POST MESSAGE" >
</form>

<form method = "GET" action ="board.php">
<input type ="submit" value="logout" name="logout">

<?php
if(isset($_GET['logout']))
{
	unset($_SESSION['login_check']);
	header("location: login.php");
}
?>
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

//try {
  //$message_dbh = new PDO("mysql:host=127.0.0.1:3306;dbname="","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


if(isset($_POST['submit_message']))
{
	
	$message = htmlspecialchars($_POST['message']);
	
	//$_SESSION['sess_message'] = $_POST['message'];
	
		//echo "enter something";
	//echo $message;
	$uid = uniqid();
	
if( strlen($_POST['message']) > 1)
{

	  $message_dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=""","","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$message_dbh->beginTransaction();

	
$message_dbh->exec("insert into posts values('$uid','null', '$_SESSION[username]', NOW(), '$message')");

$message_dbh->commit();
header("location: board.php");
}
else
{
	echo "please enter a message on the board";
	//header("location: board.php");
}

}
	else if(isset($_GET['replyto']))
	{
		
		$reply_message = htmlspecialchars($_POST['message']);
		//echo $reply_message;
		$reply_uid = uniqid();
		$replyto = $_GET['replyto'];
		
		if( strlen($_POST['message']) > 1)
		{
		
$reply_dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$reply_dbh->beginTransaction();
$reply_dbh->exec("insert into posts values('$reply_uid','$replyto', '$_SESSION[username]', NOW(), '$reply_message')");
$reply_dbh->commit();
header("location: board.php");
		}
else{
	
		header("location: board.php");
		echo "please enter a reply on the board";
}
		
	}	

?>
<?php
//echo "<p> $user </p>";
?>

<table border ="1">
<tbody>

<?php
$user = (string)$_SESSION['username'];

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
 // print_r($dbh);
  $dbh->beginTransaction();
  $dbh->exec('delete from users where username="smith"');
  $dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")');
        //or die(print_r($dbh->errorInfo(), true));
  //$dbh->commit();

  $stmt = $dbh->prepare("select id, username, fullname, datetime, replyto, message from users, posts where users.username=posts.postedby order by  datetime desc");
    //$stmt = $dbh->prepare('select id, postedby, fullname, datetime, replyto, message from posts, users');

  $stmt->execute();
  print "<pre>";
  while ($row = $stmt->fetch()) {
	  $SESSION['reply_id'] = $row['id'];
    echo "<tr>";
	echo "<td> $row[id] </td>";
	echo "<td> $row[username] </td>";
	echo "<td>$row[fullname] </td>";
	echo "<td>$row[datetime]</td>";
	if($row[4]=='null')
		echo "<td width=\"100\">   </td>";
	else
	echo "<td>$row[replyto]</td>";
	echo "<td>$row[message]</td>";

	//echo "<td> <form id=\"222\" method=\"GET\" action=\"board.php\">";
	echo "<td> <input type=\"submit\"name =\"rr\" form=\"111\" value=\"reply\" formaction=\"board.php?replyto=$SESSION[reply_id]\"></form></td>";
	echo "</tr>";
  }
  
  
  print "</pre>";
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

?>

</tbody>
</table>

</body>
</html>
