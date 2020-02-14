<?php 
if(isset($_GET))
{
	include_once "settings.php";
	require_once('../class/db.class.php');
	DB::conn();

	$userOnline = (int)$_GET['user'];
	$timestamp = ($_GET['timestamp']) == 0 ? time() : strip_tags(trim($_GET['timestamp']));
	$lastid = (isset($_GET['lastid']) && !empty($_GET['lastid'])) ? $_GET['lastid'] : 0;
	$usersOn = array();

	$agora = date('Y-m-d H:i:s');
	$expira = date('Y-m-d H:i:s',strtotime('+1 min'));
	$upOnline = DB::conn()->prepare("UPDATE `usuarios` SET `limite` = ? WHERE `id` = ?");
	$upOnline->execute(array($expira, $userOnline));

	$pegaOnline = DB::conn()->prepare("SELECT * FROM `usuarios` WHERE `id` != '$userOnline' ORDER BY `id` DESC");
	$pegaOnline->execute();

	while($onlines = $pegaOnline->fetch())
	{
		$foto = ($onlines['foto'] == '') ? 'eldog.png' : $onlines['foto'];
		$blocks = explode(',', $onlines['blocks']);
		if(!in_array($userOnline, $blocks))
		{
			if($agora >= $onlines['limite'])
			{
				$usersOn[] = array('id' => $onlines['id'], 'nome' => utf8_encode($onlines['nome']), 'foto' => $foto, 'status' => 'off');
			}
			else{
				$usersOn[] = array('id' => $onlines['id'], 'nome' => utf8_encode($onlines['nome']), 'foto' => $foto, 'status' => 'on');
			}
		}
	}
	
	if(empty($timestamp)){
		die(json_encode(array('status' => 'erro')));
	}
	$tempoGasto = 0;
	$lastidQuery = '';
	if(!empty($lastid)){
		$lastidQuery = ' AND `id` > '. $lastid;
	}
	if($_GET['timestamp'] == 0){
		$verifica = DB::conn()->prepare("SELECT * FROM `mensagens` WHERE lido = 0 ORDER BY `id` DESC");
	}else{
		$verifica = DB::conn()->prepare("SELECT * FROM `mensagens` WHERE `time` >= $timestamp". $lastidQuery. " AND `lido` = 0 ORDER BY `id` DESC");
	}
	$verifica->execute(); 
	$resultados = $verifica->rowCount();

	if($resultados <= 0){
		while($resultados <= 0){
			if($resultados <= 0)
			{
				if($tempoGasto >= 30)
				{
					die(json_encode(array('status' => 'vazio', 'lastid' => 0, 'timestamp' => time(), 'users' => $usersOn)));
					exit;
				}
				sleep(1);
				$verifica == DB::conn()->prepare("SELECT * FROM `mensagens` WHERE `time` >= $timestamp". $lastidQuery. " AND `lido` = 0 ORDER BY `id` DESC");
				$verifica->execute(); 
				$resultados = $verifica->rowCount();
				$tempoGasto += 1;
			}
		}
	}

	$novasMensagens = array();
	if($resultados >= 1)
	{
		$emojis = array(':D',':@',':(');
			$imgs = array(
			'<img src="emoji/happy.png" width="14" />',
			'<img src="emoji/angry.png" width="14" />',
			'<img src="emoji/sad.png" width="14 />',);

		while($row = $verifica->fetch())
		{
			$fotoUser = '';
			$janela_de = 0;

			if($userOnline == $row['id_de']){
				$janela_de = $row['id_para'];
			}
			elseif($userOnline == $row['id_para']){
				$janela_de = $row['id_de'];
				$pegaUser = DB::conn()->prepare("SELECT `foto` FROM `usuarios` WHERE `id` = '$row[id_de]'");
				$pegaUser->execute();

				while($usr = $pegaUser->fetch()){
					$fotoUser = ($usr['foto'] == '') ? 'eldog.png' : $usr['foto'];
				}
			}
			$msg = str_replace($emojis, $imgs, $row['mensagem']);
			$novasMensagens[] = array(
				'id'=> $row['id'],
				'mensagem' => utf8_encode($msg),
				'fotoUser' => $fotoUser,
				'id_de' => $row['id_de'],
				'id_para' => $row['id_para'],
				'janela_de' => $janela_de
			);
		}	
	}
	$ultimaMsg  = end($novasMensagens);
	$ultimoid = $ultimaMsg['id'];
	die(json_encode(array('status' => 'resultados', 'timestamp' => time(), 'lastid' => $ultimoid, 'dados' => $novasMensagens, 'users' => $usersOn)));
}
?>