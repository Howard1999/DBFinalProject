<title>Board Manage</title>
<h1 align="center">Board Manage</h1>
<form id="board_create_table" action="create_board.php" method="post" align="center">
	<label for="board_name">create new board :</label>
	<input type="text" name="board_name" placeholder="board name">
	<input type="submit" value="create">
</form>
<form id="delete_board_table" action="delete_board.php" method="post">
	<input type="hidden" id="delete_board_name" name="board_name" value="">
</form>
<form id="rename_board_table" action="rename_board.php" method="post">
	<input type="hidden" id="old_board_name" name="old_board_name" value="">
	<input type="hidden" id="new_board_name" name="new_board_name" value="">
</form>
<table style="width:50%" align="center">
	<tr><th>board name</th><th>rename</th><th>delete</th>
<?php
	session_start();
	//connect to database
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
	
	// check user authority
	$is_admin = false;
	if(isset($_SESSION['session_id'])){
		$result = $conn->query('select user_name from user where session_id="'.$_SESSION['session_id'].'" and user_authority = "A"');
		if($row = $result->fetch_row()){
			$user_name = $row[0];
			$is_admin = true;
		}
		$result->close();
	}
	if(!$is_admin){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	// genarate all board
	$result = $conn->query('select board_name from board order by board_name');
	while($row = $result->fetch_row()){
		echo '<tr>';
			echo '<td align="center">'.$row[0].'</td>';
			echo '<td align="center">';
			echo '<input type="text" name="new_board_name" placeholder="new board name" id="new_'.$row[0].'">';
			echo '<button onclick="rename_board(\''.$row[0].'\')">rename</button>';
			echo '</td>';
			echo '<td align="center"><button onclick="delete_board(\''.$row[0].'\')">delete</button></td>';
		echo '</tr>';
	}
	// alert err_msg
	if(isset($_GET['err_msg'])){
		if($_GET['err_msg']=="board_exist"){
			echo "<script type='text/javascript'>alert('這個版已經存在');</script>";
		}
		else if($_GET['err_msg']=='name_empty'){
			echo "<script type='text/javascript'>alert('版名不可為空');</script>";
		}
		else if($_GET['err_msg']=='name_too_long'){
			echo "<script type='text/javascript'>alert('版名太長(最多20個字)');</script>";
		}
		else if($_GET['err_msg']=='unknown'){
			echo "<script type='text/javascript'>alert('發生未知錯誤請稍後再試');</script>";
		}
	}
?>
</table>
<script type="text/javascript">
	function delete_board(board_name){
		if(window.confirm("這將無法回復，確定要執行嗎?")){
			const form = document.getElementById('delete_board_table');
			const hiddenField = document.getElementById('delete_board_name');
			hiddenField.value = board_name;
			form.submit();
		}
	}
	function rename_board(board_name){
		const form = document.getElementById('rename_board_table');
		const hiddenField = document.getElementById('new_board_name');
		const hiddenField2 = document.getElementById('old_board_name');
		const input = document.getElementById('new_'+board_name);
		hiddenField.value = input.value;
		hiddenField2.value = board_name;
		form.submit();
	}
</script>