<form action="profile_page.php" method="post">
  <input type="text" name="user_name" placeholder="輸入使用者名稱："/><br/> 
  <input type="text" name="password" placeholder="輸入新密碼："/><br/> 
  
  <input type="submit" name="update" value="更改"/>
</form>

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

if(isset($_POST['update'])){
    $account = $_POST['account'];
  
    $query = "UPDATE 'user' SET user_name='$_POST[user_name]',password='$_POST[password]' WHERE account='$_POST[account]'";
    $query_run = mysqli_query($conn,$query)
      
    if($query_run){
      echo '<script type="text/javascript"> alert("Data Updated") </script>';
    }else{
      echo '<script type="text/javascript"> alert("Data No Updated") </script>';
    }
}
?>
