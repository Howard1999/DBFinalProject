<?php
	session_start();
	$account = $_SESSION['account'];
	$servername = "127.0.0.1";
	$username = "team1";
	$password = "DB338HKkvRVOZzb";
	$dbname = "team1";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	if (!$conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $conn->error);
		exit();
	}

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	
	$sql="SELECT * FROM users WHERE account = '$account'";
	

if ($result = mysqli_query($conn,$sql))
{
	while($r = mysqli_fetch_row($result)) {
	    echo '<div class="col-sm-6 col-md-6 col-lg-3 membersGrid">';
	    
		printf ("個人資料");
		echo '<br>';
		printf ("使用者名稱 : %s",$r[0]);
		echo '<br>';
		printf ("帳號 : %s",$r[1]);
		echo '<br>';
		printf ("密碼 : %s",$r[2]);
		echo '<br>';
		printf ("使用者權限 : %s",$r[3]);
		echo '<br>';
		printf ("擁有版名 : %s",$r[6]);
        echo "<br>";
	    echo '</div>';
	}
	mysqli_free_result($result);
}
?>
