<?php 
	session_start();
	include_once "sys/settings.php";
	require_once('class/db.class.php');
	DB::conn();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat System</title>
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
			height: 200px;
			background: white;
			border-radius: 6px;
			margin-left: -250px;
			margin-top: -100px;
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
				$email = strip_tags(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING)));
				if($email == ''){
					echo 'Informe o email';
				}
				else{
					$pegaUser = DB::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
					$pegaUser->execute(array($email));

					if($pegaUser->rowCount() == 0){
						echo 'Usuário não encontrado!';
					}
					else
					{
						$agora = date('Y-m-d H:i:s');
						$limite = date('Y-m-d H:i:s', strtotime('+2 min'));
						$update = DB::conn()->prepare("UPDATE `usuarios` SET `horario` = ?, `limite` = ? WHERE `email` = ?");
						if($update->execute(array($agora,$limite,$email))){
						while($row = $pegaUser->fetchObject())
						{
							$_SESSION['email_logado'] = $email;
							$_SESSION['id_user'] = $row->id;
							header("Location: chat.php");
							//echo '<script>location.href="";</script>';
						}
						} // if update
					}
				}
			}
		?>
		<h1> Welcome to Chat, Please login it</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<label>
				<span>Email: </span>
				<input type="text" name="email" placeholder="Your email here">
			</label>
			<input type="hidden" name="action" value="logar"/>
			<input type="submit" value="Login" class='button right'/>
		</form>
	</div>
<body>

</body>
</html>