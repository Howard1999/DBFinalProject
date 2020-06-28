<!doctype html>
<html>
<style type="text/css">

#main {
  max-width: 600px;
  margin: 0 auto; 
}
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
body{font-family: arial,"Microsoft JhengHei","微軟正黑體",sans-serif !important;}
#main_page_link{
	display:inline-block;
}
#logout_link{
	display:inline-block;
}
#building_title_link{
	display:inline-block;
	width:40px;
	height:40px;
	border:2px #9999FF solid;
}
#building_author{
	display:inline-block;
	
}
#building_author{
	display:inline-block;
	
}
#article_building{
	display:inline-block;
	
}
</style>


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
	echo '<a id="main_page_link" href="/DBFinalProject/index.php"><input type="button" value="回到主頁" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
	$login = false;
	if(isset($_SESSION['session_id'])){//login check
		$stmt = $conn->prepare("select user_name from user where session_id = ?");
		$stmt->bind_param("s",$_SESSION['session_id']);
		$stmt->execute();
		$stmt->bind_result($user);
		if($stmt->fetch()){
			echo '<a id="logout_link" href="/DBFinalProject/logout.php"><input type="button" value="登出" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
			$login = true;
		}
		$stmt->close();
	}
	if(!$login){
		echo '<a id="login_page_link" href="/DBFinalProject/login_page.php"><input type="button" value="登入" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
		echo '<a id="register_page_link" href="/DBFinalProject/register_page.php"><input type="button" value="前往註冊" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
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
				echo '<a id="logout_link" href="/DBFinalProject/post_page.php?board_name='.$board.'"><input type="button" value="發表文章" style="width:120px;height:40px;border:2px #9999FF dashed;background-color:pink;"></a>';
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
	echo '<div align="center">';
	echo '<h1 id="board_name">'.$board.'</h1>';
	echo '<p id="popularity">人氣:'.$popularity.'</p>';
	echo '</header>';
	echo '</div>';
	// header end
	// sort keys
	echo '<div align="center">';
	echo '<form id="order" action="board.php" method="get">';
	echo '<input type=hidden name="board_name" value="'.$board.'">';
	echo '<input type=hidden name="page" value="'.$page.'">';
	?>
			<select class="form control" name="order_key" name="order_key" style="background-color:#BBDCEC">
				<option value="create_time" style="color:black;font-weight:black">創建時間</option>
				<option value="account" style="color:black;font-weight:black">作者</option>
				<option value="title" style="color:black;font-weight:black">標題</option>
			</select>
			<select name="order_type" name="order_type" style="background-color:#BBDCEC">
				<option value="asc" style="color:black;font-weight:black">升順</option>
				<option value="desc" style="color:black;font-weight:black">降順</option>
			</select>
			<input id="order" type="submit" value="排序" style="width:60px;height:20px;border:2px #000000 solid;background-color:white;">
		</form>
		</div>
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
	
	echo '<section id="article_building_list" style="margin:15px;">';
	while($stmt->fetch()){
		// an article building section
		echo '<div align="center">';
		echo '<section class="article_building"  style="background-color:gray;width:700px;border:10px;">';
		echo '<a class="building_title_link" href="/DBFinalProject/article_building.php?building_ID='.$building_ID.'"><input type="button" value="'.$title.'" style="width:400px;height:40px; color:#CEA107; background-color:#05143D;"><br></a>';
		echo '<p class="building_author">作者:'.$user_name.'</p>';
		echo '<p class="building_create_time">發布時間:'.$create_time.'</p>';
		echo '</section>';
		echo '</div>';
	}
	echo '</section>';
	$stmt->close();
	echo '<div align="center">';
	// if there are no any page then show a post link
	if($total_page==0){
		echo '<a id="no_article_prompt" href="/DBFinalProject/post_page.php?board_name='.$board.'"><input type="button" value="這裡還沒有任何文章..發佈第一篇" style="width:400px;height:40px; color:#CEA107; background-color:#05143D;"><br></a>';
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
	echo '</	div>';
	$conn->close();
?>
<style>
	.page_footer{
		display:inline;
	}
</style>
</body>
</html>
