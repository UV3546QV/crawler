<?php 
require "header.php";
$email = $_GET['email'];
$token = stripslashes($_POST['sec-code']);
$validate_code = true;
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'crawler');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', '');

if (isset($_POST['activation'])){
    $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
       if (mysqli_connect_errno()) {
           echo("<p>Error creating Database Connection</p>");
           exit;
       }
      
       $sql = "select * from users";
       $sql2 = "UPDATE users SET active='yes' WHERE email='$email';";
       $result = $conn->query ($sql);
       
       if ((!$result)) {
           echo "Cannot creaste Database";
           exit;
       }
       
       $numRows = $result -> num_rows;
       
       for($i = 0; $i < $numRows; $i++){

       		$row = $result -> fetch_array();
       		if(strcasecmp($row[2], $email) == 0 && strcmp($row[6], $token) == 0 && strcasecmp($row[7], 'no') == 0){
       			$validate_code = true;
       			break;
       		}
       		else
       			$validate_code = false;
       }
       if($validate_code == true){
       		$result2 = $conn->query ($sql2);
       		 if ((!$result2)) {
           		echo "Cannot creamte Database";
           	}
           	else
           		header("Location: /login.php");
       		
       	}
       else if($validate_code == false)
       		echo "Security Code Entered is Invalid";
       
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>registrer | spider</title>
	<style>
		form{
			margin-left: 20px;
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<form action="<? $_SERVER['PHP_SELF'] ?>" method="post" name="activation-form">
		<span>Enter Security Code to Activate Account</span><br />
		<input type="text" id="activation-text" name="sec-code" placeholder="Enter Security Code here">
		<input id="act-submit" name="activation" type="submit" value="Activate">
	</form>
</body>
</html>