<?php 
	session_start();
	include_once "sys/settings.php";
	require_once('sys/db.class.php');
    DB::conn();
    
    if(isset($_SESSION['username'], $_SESSION['userid']))
		header('Location: index.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Game Lobby System</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript">
		$.noConflit();
	</script>
	<style type="text/css">
		*{margin: 0; padding: 0; box-sizing: border-box;}
		body {background: #ebebeb;}
		.right{float: right;}
		.button{padding: 6px 8px; background: linear-gradient(to bottom, #069, #09f 130%); border: 1px solid white;font: 16px tahoma, arial; color: white; border-radius: 5px;}
		.form
		{
			position: absolute;
			top: 50%;
			left: 50%;
			width: 500px;
			height: 250px;
			background: white;
			border-radius: 6px;
			margin-left: -250px;
			margin-top: -125px;
			padding: 10px;
			box-shadow: #ccc 2px 1px 20px;
		}
		h1
		{
			float: left;
			width: 100%;
			margin-bottom: 10px;
			font: 24px "Trebuchet MS", tahoma;
			font-weight: bold;
			color: #069;
			padding: 5px;
			text-align: center;
		}
		.form label
		{
			float: left;
			width: 100%;
		}
		.form label span
		{
			float: left;
			width: 100%;
			font: 15px Tahoma, arial;
			color: #555;
			margin-bottom: 10px;
		}
		.form label input
		{
			float: left;
			width: 100%;
			padding: 6px;
			background: white;
			border-radius: 3px;
			border: 1px solid #999;
			outline: none;
			margin-bottom: 10px;
		}
	</style>
</head>
<body>
	<div class="form">
		<?php
			if(isset($_POST['action']) && $_POST['action'] == 'logar')
			{
				$username = strip_tags(trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)));
				$password = strip_tags(trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)));
				if($username == ''){
					echo 'Enter your username:';
				}
				elseif($password == '')
					echo 'Enter your password:';
				else{
					$pegaUser = DB::conn()->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
					$pegaUser->execute(array($username, $password));

					if($pegaUser->rowCount() == 0){
						echo 'Incorrect user or password!';
					}
					else
					{
						//$agora = date('Y-m-d H:i:s');
						//$limite = date('Y-m-d H:i:s', strtotime('+2 min'));
						//$update = DB::conn()->prepare("UPDATE `usuarios` SET `horario` = ?, `limite` = ? WHERE `email` = ?");
						//if($update->execute(array($agora,$limite,$email))){
						while($row = $pegaUser->fetchObject())
						{
							$_SESSION['username'] = $username;
							$_SESSION['userid'] = $row->id;
							$_SESSION['nickname'] = $row->nickname;
							header("Location: index.php");
							//echo '<script>location.href="";</script>';
						}
						} // if update
					}
			}
		?>
		<h1>Game Lobby System</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<label>
				<span>Username: </span>
				<input type="text" name="username" placeholder="Your username here">
			</label>
			<label>
				<span>Password: </span>
				<input type="password" name="password" placeholder="Your password here">
			</label>
			<input type="hidden" name="action" value="logar"/>
			<input type="submit" value="Login" class='button right'/>
		</form>
	</div>
<body>

</body>
</html>