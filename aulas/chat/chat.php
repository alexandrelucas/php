<?php 
	session_start();
	include_once "sys/settings.php";
	require_once('class/db.class.php');
	DB::conn();
	if(!isset($_SESSION['email_logado'], $_SESSION['id_user'])){
		header('Location: index.php');
	}
	$pegaUser = DB::conn()->prepare("SELECT * FROM `usuarios` WHERE `email` = ?");
	$pegaUser->execute(array($_SESSION['email_logado']));
	$dadosUser = $pegaUser->fetch();

	if(isset($_GET['acao']) && $_GET['acao'] == 'sair')
	{
		unset($_SESSION['email_logado']);
		unset($_SESSION['id_user']);
		session_destroy();
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Chat System</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/jquery_play.js"></script>
</head>
<body>
	<span class="user_online" id="<?php echo $dadosUser['id']; ?>"></span>
	<a href="?acao=sair">Logoff</a>
	<aside id="users_online">
		<ul>
			<?php
				$pegaUsuarios = DB::conn()->prepare("SELECT * FROM `usuarios` WHERE `id` != ?");
				$pegaUsuarios->execute(array($_SESSION['id_user']));
				while($row = $pegaUsuarios->fetch())
				{
					$foto = ($row['foto'] == '') ? 'eldog.png' : $row['foto'];
					$blocks = explode(',', $row['blocks']);
					$agora = date('Y-m-d H:i:s');
					if(!in_array($_SESSION['id_user'], $blocks))
					{
						$status = 'on';
						if($agora >= $row['limite']){
							$status = 'off';
						}
					}
					$depara = $_SESSION['id_user'] . ':'. $row['id'];
				?>
			<li id="5">
				<div class="imgSmall"><img src="imgs/profile/<?php echo $foto; ?>" border="0"></div>
				<a href="#" id="<?php echo $depara; ?>" class="startChat"><?php echo utf8_encode($row['nome']);?></a/>
				<span id="<?php echo $row['id'];?>" class="status <?php echo $status;?>"></span>
			</li>
			<?php }?>
		</ul>
	</aside>
	<aside id="chats">

	</aside>
	<script type="text/javascript" src="js/functions.js"></script>
</body>
</html>

	<!-- <div class="window" id="wndw_x">
			<div class="header_window"><a href="#" class="close">X</a><span class="name">User</span><span id="5" class="status on"></span></div>
			<div class="body_window">
				<div class="messages">	
					<ul>
						<li class="eu"><p>Este é um exemplo de mensagem que aparecerá na página.</p></li>
						<li class="">
							<div class="imgSmall"><img src="imgs/profile-pictures/5.png" border="0"></div>
							<p>Este é um exemplo de mensagem que aparecerá na página.</p></li>
					</ul>
				</div>
				<div class="send_message" id="3:5">
					<input type="text" name="mensagem" class="msg" id="3:5">
				</div>
			</div>
		</div> -->