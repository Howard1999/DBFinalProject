<?php
	session_start();
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

	$query = $db->query("SELECT * from user");

	while($r = $query->fetch()) {
	    echo '<div class="col-sm-6 col-md-6 col-lg-3 membersGrid">';
	    echo  $r['user_name'], '<br>';
		echo  $r['account'], '<br>';
		echo  $r['password'], '<br>';
	    echo  $r['user_authority'], '<br>';
		echo  $r['board_name'], '<br>';
	    echo '<a class="viewProfile" href="profile_page.php?id=' . $r['id']. '"><button>View Profile</button></a>';
	    echo '</div>';
	}

?>
