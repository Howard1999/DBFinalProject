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
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// function
	function redirect(){
		if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])){
			header("Location: /DBFinalProject/board.php?board_name=".$_SESSION['last view board']."&page=".$_SESSION['last view page']);
			die();
		}
		else{
			header("Location: /DBFinalProject/index.php");
			die();
		}
	}
	// check login
	$login = false;
	if(isset($_SESSION['session_id'])){
		$result=$conn->query('select account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row=$result->fetch_row()){
			$account = mysqli_real_escape_string($conn,$row[0]);
			$login = true;
		}
		$result->close();
	}
	if(!login){
		header("Location: /DBFinalProject/login_page.php?err_msg=post_without_login");
		die();
	}
	if((isset($_POST['board_name'])&&isset($_POST['building_ID']))||!(isset($_POST['board_name'])||isset($_POST['building_ID']))){
		redirect();
	}
	
	$success = false;
	$string_check = true;

	if(isset($_POST['title'])){
		$title = mysqli_real_escape_string($conn,$_POST['title']);
		if(strlen($title)==0){
			$string_check = false;
			$err_msg = "標題不能為空";
		}
		else if(strlen($title)>50){
			$err_msg = "標題長度需在50以下";
			$string_check = false;
		}
	}
	$content = mysqli_real_escape_string($conn,$_POST['content']);
	if(strlen($content)==10){
		$err_msg = "內文不能為空";
		$string_check = false;
	}
	else if(strlen($content)>10000){
		$err_msg = "內文限制10000字以下";
		$string_check = false;
	}
	
	if($string_check){
		// post on board
		if(isset($_POST['board_name'])){
			$board_name = mysqli_real_escape_string($conn,$_POST['board_name']);

			$conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			if($conn->query('insert into article_building values(default,"'.$board_name.'","'.$account.'","'.$title.'",current_timestamp)')){
				$building_ID = $conn->insert_id;
				if($conn->query('insert into article values(default,'.$building_ID.',"'.$account.'","'.$content.'",current_timestamp)')){
					$success = true;
				}
			}
			if(!$success){
				$conn->rollback();
			}
			$conn->commit();
		}
		// post on article building
		else if(isset($_POST['building_ID'])){
			$building_ID = mysqli_real_escape_string($conn,$_POST['building_ID']);
			if($conn->query('insert into article values(default,'.$building_ID.',"'.$account.'","'.$content.'",current_timestamp)')){
				$success = true;
			}
		}
		$conn->close();
		if($success){
			header("Location: /DBFinalProject/article_building.php?building_ID=".$building_ID);
			die();
		}
		else{
			?>
				<script type="text/javascript">
				alert("PO文失敗，請稍後再試");
				<?php
					if(isset($_POST['board_name'])){
						echo 'window.location.href ="post_page.php?board_name='.$board_name.'";';
					}
					else if(isset($_POST['building_ID'])){
						echo 'window.location.href ="post_page.php?building_ID='.$building_ID.'";';
					}
				?>
				</script>
			<?php
		}
	}
	else{
		?>
			<script type="text/javascript">
			<?php
				echo 'alert("'.$err_msg.'");';
				if(isset($_POST['board_name'])){
					$board_name = mysqli_real_escape_string($conn,$_POST['board_name']);
					echo 'window.location.href ="post_page.php?board_name='.$board_name.'";';
				}
				else if(isset($_POST['building_ID'])){
					$building_ID = mysqli_real_escape_string($conn,$_POST['building_ID']);
					echo 'window.location.href ="post_page.php?building_ID='.$building_ID.'";';
				}
			?>
			</script>
		<?php
	}
?>