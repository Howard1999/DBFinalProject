<body>
<div style="position:relative;">
        <img class="photo1" src="world.png" alt="" width="100%" height="15%">
</div>
</body>
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
	
	// check building_ID is set
	if(!isset($_GET['building_ID'])){
		header("Location: /DBFinalProject/index.php");
		die();
	}
	else{
		$building_ID = mysqli_real_escape_string($conn,$_GET['building_ID']);
	}
	// check building_ID is exist
	$query = $conn->query('select title,create_time,board_name from article_building where building_ID="'.$building_ID.'"');
	if($row = $query->fetch_row()){
		$title = $row[0];
		$create_time = $row[1];
		$board_name = $row[2];
		$conn->query('update board set popularity=popularity+1 where board_name="'.$board_name.'"');
		echo "<title>".$title."</title>";
	}
	else{
		header("Location: /DBFinalProject/index.php");
		die();
	}
	$query->close();
	// header
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php"><img src="go_index.png" border="0" alt="回到主頁" width="10%" height="10%" style="position:absolute;left:75%;top:14%;"></a>';
	// check login
	$login = false;
	if(isset($_SESSION['session_id'])){
		$query = $conn->query('select user_name,account from user where session_id="'.$_SESSION['session_id'].'"');
		if($row = $query->fetch_row()){
			$user_name = $row[0];
			$user_account = $row[1];
			$login = true;
			echo '<a id="logout_page_link" href="/DBFinalProject/logout.php"><img src="logout.png" border="0" alt="登出" width="10%" height="10%" style="position:absolute;left:80%;top:14%;"></a>';
			echo '<a id="reply_link" href="/DBFinalProject/post_page.php?building_ID='.$building_ID.'"><img src="reply.png" border="0" alt="回覆文章" width="10%" height="10%" style="position:absolute;left:83%;top:14%;"></a>';
		}
		$query->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php"><img src="login.png" border="0" alt="登入" width="10%" height="10%" style="position:absolute;left:80%;top:14%;"></a>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php"><img src="go_register.png" border="0" alt="前往註冊" width="10%" height="10%" style="position:absolute;left:83%;top:14%;"></a>';
	}
	
	if(isset($_SESSION['last view board'])&&isset($_SESSION['last view page'])&&isset($_SESSION['order_key'])&&isset($_SESSION['order_type'])){
		echo '<a id="board_link" href="/DBFinalProject/board.php?board_name='.$_SESSION['last view board'].'&page='.$_SESSION['last view page'].'&order_key='.$_SESSION['order_key'].'&order_type='.$_SESSION['order_type'].'">回到版上</a>';
	}
	else{
		echo '<a id="reply_link" href="/DBFinalProject/board.php?board_name='.$board_name.'"><img src="go_board.png" border="0" alt="回到版上" width="10%" height="10%" style="position:absolute;left:88%;top:14%;"></a>';
	}
	//
	echo '<h1 id="article_building_title">'.$title.'</h1>';
	echo '<h5 id="create_time">創建時間:'.$create_time.'</h5>';
	echo '</header>';
	// header end
	
	// show article
	$floor_count = 1;
	$query = $conn->query('select user.account,content,last_edit_time,user_name,article_ID from article natural join user where building_ID='.$building_ID.' order by article_ID');
	echo '<section id="article_list">';
	while($row = $query->fetch_row()){
		$account = $row[0];
		$content = $row[1];
		$last_edit = $row[2];
		$author = $row[3];
		$article_ID = $row[4];
		$space = '&nbsp;&nbsp;&nbsp;&nbsp';
		// an article section
		echo '<section class="article">';
		echo '<p class="article_header">'.$floor_count.'樓'.$space.'作者: '.$author.$space.'最後編輯: '.$last_edit.$space;
		if($account==$user_account)
			echo '<a class="edit_link" href="/DBFinalProject/edit_page.php?building_ID='.$building_ID.'&article_ID='.$article_ID.'">編輯</a>';
		echo '</p>';
		echo '<section class="article_header">';
		// like dislike
		$temp=$conn->query('select count(type) from like_dislike where article_ID='.$article_ID.' and type=true');
		$like_count = $temp->fetch_row();
		$like_count = $like_count[0];
		$temp->close();
		$temp=$conn->query('select count(type) from like_dislike where article_ID='.$article_ID.' and type=false');
		$dislike_count = $temp->fetch_row();
		$dislike_count = $dislike_count[0];
		$temp->close();
		echo '<section calss="like_dislike">';
			echo '<p>';
				echo '推:'.$like_count.$space.' 噓:'.$dislike_count.$space;
				echo '<a href="/DBFinalProject/like.php?article_ID='.$article_ID.'">推</a>';
				echo $space.$space;
				echo '<a href="/DBFinalProject/dislike.php?article_ID='.$article_ID.'">噓他</a>';
			echo '</p>';
		echo '</section>';
		// article content
		echo '<textarea class="article_content" cols= "60" rows="10" disabled>'.$content.'</textarea>';
		// comment
		$result = $conn->query('select user_name,content,post_time from comment natural join user where article_ID='.$article_ID.' order by post_time');
		echo '<br><select class="comment_box" size=3 style="width:445">';
			while($comment = $result->fetch_row()){
				echo '<option>';
					echo $comment[0].': '.$comment[1];
				echo '</option>';
			}
			echo '</select>';
		echo '</section>';
		// input comment
		echo '<form calss="comment_input" action="leave_comment.php" method="post">';
			echo '<input type="hidden" name="article_ID" value="'.$article_ID.'">';
			echo '<input type="text" name="comment" placeholder="留言">';
			echo '<input type="submit" value="送出">';
		echo '</form>';
		$floor_count++;
	}
	echo '</section>';
	$query->close();

	$conn->close();
?>

<style type="text/css">
body {
	width: 100%;
	height: 100%;
	padding: 0;
	margin: 0;
	background: url("https://img.rawpixel.com/s3fs-private/rawpixel_images/website_content/pf-misctexture01-beer-000_5.jpg?w=800&dpr=1&fit=default&crop=default&q=65&vib=3&con=3&usm=15&bg=F4F4F3&ixlib=js-2.2.1&s=c1552a7bdc2ea7b6e17d8d0d893c15be");
	background-size: cover;
	background-attachment: fixed;
	background-position: center;
}
textarea {
	resize:none;
}
.article_content{
	resize:none;
}
</style>
