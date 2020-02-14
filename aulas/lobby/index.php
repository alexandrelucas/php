<?php 
	session_start();
	include_once "sys/settings.php";
	require_once('sys/db.class.php');
	DB::conn();
	if(!isset($_SESSION['username'], $_SESSION['userid'])){
		header('Location: login.php');
	}
	$grabUser = DB::conn()->prepare("SELECT * FROM `users` WHERE `username` = ?");
	$grabUser->execute(array($_SESSION['username']));
	$dadosUser = $grabUser->fetch();

	$userPicture = $dadosUser['picture'];

	if(isset($_GET['logout']) && $_GET['logout'] == '1')
	{
		unset($_SESSION['username']);
		unset($_SESSION['userid']);
		session_destroy();
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Welcome, <?php echo $_SESSION['username'];?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
</head>
<body>
	<nav class="navbar">
		<div class="settings hidden">
			<ul>
				<li><span class="username"><?php echo $_SESSION['nickname'];?></span></li>
				<li><a href="?settings=1" ><img src="imgs/sys/settings-24.png" border="0" width="20"/>Settings</a></li>
				<li><a href="?logout=1" ><img src="imgs/sys/logout-24.png" border="0" width="20"/>Log Out</a></li>
			</ul>
		</div>
		<div class="imgSmall"><img src="imgs/picture/<?php echo $userPicture; ?>" border=0 /></div>
	</nav>
	<main class="main">
		<section class="leftside">
			<div class="roomlist">
				<?php 
					for($i = 0 ; $i < 3; $i++)
					{
						echo "<ul><li>";
						for($j = 0; $j < 3; $j++)
						{
							echo '<div class="room" id="room_id"></div>';
						}
						echo "</li></ul>";
					}
				?>
			</div>
		</section>
		<aside class="rightside">
		</aside>
	</main>
	<script type="text/javascript" src="js/functions.js"></script>
</body>
</html>