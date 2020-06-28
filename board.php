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
	// header start 
	// page link
	echo '<header id="header">';
	echo '<a id="main_page_link" href="/DBFinalProject/index.php">回到主頁</a>';
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$stmt = $conn->prepare("select user_name from user where session_id = ?");
		$stmt->bind_param("s",$_SESSION['session_id']);
		$stmt->execute();
		$stmt->bind_result($user);
		if($stmt->fetch()){
			echo '<a id="logout_link" href="/DBFinalProject/logout.php">登出</a>';
			$login = true;
		}
		$stmt->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php">登入</a>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php">前往註冊</a>';
	}

	//check board exist
	if(isset($_GET["board_name"])){
		$stmt = $conn->prepare("select * from board where board_name=?");
		$stmt->bind_param("s",$_GET["board_name"]);
		$stmt->execute();
		$stmt->bind_result($board,$popularity);
		if($stmt->fetch()){
			$_SESSION["last view board"] = $board;
			echo "<title>".$board."</title>";
			$stmt->close();
			if($login){
				// if login then he can post article
				echo '<a id="logout_link" href="/DBFinalProject/post_page.php?board_name='.$board.'">發表文章</a>';
			}
		}
		else{
			header("Location: /DBFinalProject/index.php");
			die();
		}
	}
	else{
		header("Location: /DBFinalProject/index.php");
		die();
	}
	//check page is assign
	$page=1;
	$page_exist = false;
	if(isset($_GET["page"])){
		$page = $_GET["page"];
	}
	//check page is enough
	$total_page = 0;
	$article_building_per_page = 10;
	
	$stmt = $conn->prepare("select count(building_ID) from article_building where board_name = ?");
	$stmt->bind_param("s",$board);
	$stmt->execute();
	$stmt->bind_result($num_of_building);
	if($stmt->fetch()){
		$stmt->close();
		$total_page = ceil($num_of_building/$article_building_per_page);
		if((0<$page&&$page<=$total_page)||(1==$page)){
			$_SESSION["last view page"] = $page;
			$page_exist = true;
		}
	}
	//if not enough or not assign goto page 1
	if(!$page_exist){
		header("Location: /DBFinalProject/board.php?board_name=$board&page=1");
		die();
	}
	
	//
	echo '<h1 id="board_name">'.$board.'</h1>';
	echo '<p id="popularity">人氣:'.$popularity.'</p>';
	echo '</header>';
	// header end
	// sort keys
	echo '<form id="order" action="board.php" method="get">';
	echo '<input type=hidden name="board_name" value="'.$board.'">';
	echo '<input type=hidden name="page" value="'.$page.'">';
	?>
			<select name="order_key" name="order_key">
				<option value="create_time">創建時間</option>
				<option value="account">作者</option>
				<option value="title">標題</option>
			</select>
			<select name="order_type" name="order_type">
				<option value="asc">升順</option>
				<option value="desc">降順</option>
			</select>
			<input id="order" type="submit" value="排序">
		</form>
	<?php
	//genarate article building link
	$order_key = 'title';
	$order_type = 'asc';
	if(isset($_GET['order_key']))$order_key=$_GET['order_key'];
	if(isset($_GET['order_type']))$order_type=$_GET['order_type'];

	$_SESSION['order_key'] = $order_key;
	$_SESSION['order_type'] = $order_type;

	$offset = ($page-1)*$article_building_per_page;
	$stmt = $conn->prepare("select building_ID,title,user_name,create_time from article_building inner join user using(account) where article_building.board_name=? order by ".$order_key." ".$order_type." limit ? offset ?");
	$stmt->bind_param("sii",$board,$article_building_per_page,$offset);
	$stmt->execute();
	$stmt->bind_result($building_ID,$title,$user_name,$create_time);
	echo '<section id="article_building_list">';
	while($stmt->fetch()){
		// an article building section
		echo '<section class="article_building">';
		echo '<a class="building_title_link" href="/DBFinalProject/article_building.php?building_ID='.$building_ID.'">'.$title.'</a>';
		echo '<p class="building_author">作者:'.$user_name.'</p>';
		echo '<p class="building_create_time">發布時間:'.$create_time.'</p>';
		echo '</section>';
	}
	echo '</section>';
	$stmt->close();
	// if there are no any page then show a post link
	if($total_page==0){
		echo '<a id="no_article_prompt" href="/DBFinalProject/post_page.php?board_name='.$board.'">這裡還沒有任何文章，發表第一篇</a>';
	}//other wise give user previous, next page link and show where is the page now
	else{
		echo '<section id="page_footer">';
		// previous page
		if($page>1){
			$pre_page = $page-1;
			echo '<a id="pre_page" class="page_footer" href="/DBFinalProject/board.php?board_name='.$board.'&page='.$pre_page.'&order_key='.$order_key.'&order_type='.$order_type.'">上一頁</a>';
		}
		else{
			echo '<a id="pre_page" class="page_footer">上一頁</a>';
		}
		// page now
		echo '<form class="page_footer" method="get" action="board.php">';
		echo '<input id="page_input" name="page" type="text" size=1 placeholder="'.$page.'/'.$total_page.'" onkeyup="value=value.replace(/^(0+)|[^\d]+/g,\'\')">';
		echo '<input name="board_name" type="hidden" value="'.$board.'">';
		echo '<input name="order_key" type="hidden" value="'.$order_key.'">';
		echo '<input name="order_type" type="hidden" value="'.$order_type.'">';
		echo '</form>';
		//echo '<a id="page" >第'.$page.'頁</a>';
		// next page
		if($page<$total_page){
			$next_page = $page+1;
			echo '<a id="next_page" class="page_footer" href="/DBFinalProject/board.php?board_name='.$board.'&page='.$next_page.'&order_key='.$order_key.'&order_type='.$order_type.'">下一頁</a>';
		}
		else{
			echo '<a id="next_page" class="page_footer">下一頁</a>';
		}
		echo '</section>';
	}
	$conn->close();
?>
<style>
	.page_footer{
		display:inline;
	}
</style>