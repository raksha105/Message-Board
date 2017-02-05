
<html>
<head>
Login here
</head>

<body>

<form method = "POST" action = "login.php">

Username: <input type="text" name = "username">
<br></br>

Password: <input type ="password" name = "pswd">

<input type="submit" name ="login" value="login">
</form> 

</body>

</html> 

<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors','On');

if(isset($_POST['login']))
{

//print_r($_POST['username']);



  $new_dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=""","","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$new_dbh->beginTransaction();
$stmt = $new_dbh->prepare("select * from users where username='".$_POST['username']."' AND password='".md5($_POST['pswd'])."'");
  $stmt->execute();
  print "<pre>";
 
 $rows = $stmt->fetch(PDO::FETCH_NUM);
 $_SESSION['username'] = $rows[0];
 //print_r($_SESSION['username']);
if($rows > 0) {
	$_SESSION['login_check'] =true;
header("location: board.php");
}

else {
	//echo "<p> wrong id or pwd</p>";
	header("location: login.php");
	
//}
}
}
?>
  
