<?php
if(isset($_POST['conversacom']))
{
	include_once "settings.php";
	require_once('../class/db.class.php');
	DB::conn();

	$mensagens = array();
	$id_conversa = (int)$_POST['conversacom'];
	$online = (int)$_POST['online'];
	$pegaConversas = DB::conn()->prepare("SELECT * FROM `mensagens` WHERE (`id_de` = ? AND `id_para` = ?) OR (`id_de` = ? AND `id_para` = ?) ORDER BY `id` ASC LIMIT 10");
	$pegaConversas->execute(array($online,$id_conversa,$id_conversa, $online));

	while ($row = $pegaConversas->fetch())
	{
		$fotoUser = '';
		
		if ($online == $row['id_de'])
		{
			$janela_de = $row['id_para'];
		}
		elseif ($online == $row['id_para'])
		{
			$janela_de = $row['id_de'];
			$pegaFoto = DB::conn()->prepare("SELECT `foto` FROM `usuarios` WHERE `id` = ?");
			$pegaFoto->execute(array($row['id_de']));
			while ($usr = $pegaFoto->fetch())
			{
				$fotoUser = ($usr['foto'] == '') ? 'eldog.png' : $usr['foto'];
			}
		}
		$emojis = array(':D',':@',':(');
		$imgs = array(
			'<img src="emoji/happy.png" width="14"/>',
			'<img src="emoji/angry.png" width="14"/>',
			'<img src="emoji/sad.png" width="14//>',);

		$msg = str_replace($emojis, $imgs, $row['mensagem']);
		$mensagens[] = array(
			'id' => $row['id'],
			'mensagem' => utf8_encode($msg),
			'fotoUser' => $fotoUser,
			'id_de' => $row['id_de'],
			'id_para' => $row['id_para'],
			'janela_de' => $janela_de);
	}
	die ( json_encode($mensagens));
}
?>