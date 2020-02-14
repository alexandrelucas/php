<!DOCTYPE HTML>
<html lang="pt-BR">
	<head>
		<meta charset=UTF-8>
		<title>Mini Twitter</title>
		<link href='//fonts.googleapis.com/css?family=Roboto:100,300' rel='stylesheet' type='text/css' />
		<link href="css/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="wrapper">
		<aside id="trending">
			<h2>Trending Topics</h2>
			<ul>
				<li><a href="#">#downs_master</a></li>
				<li><a href="#">#SegundaTag</a></li>
				<li><a href="#">#mais_umaTag</a></li>
				<li><a href="#">#OutraTag</a></li>
				<li><a href="#">#alguma_coisa</a></li>
			</ul>
		</aside>
		<section id="content_wrapper">
			<section id="envio_mensagem">
				<form action="" method="post" enctype="multipart/form-data">
					<label>
						<span class="title">Digite uma mensagem</span>
						<textarea name="mensagem" class="msg"></textarea>
						<span class="counter"></span>
						<button class="send_message">Enviar</button>
					</label>
				</form>
			</section>

			<section id="content">
				<?php for($n=0; $n < 10; $n++):?>
				<article class="tweet">
					<span class="nome"><a href="#">Lucas Silva</a> disse:</span>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text </p>
					<span class="date">24/11/2015 às 11:42</span>
				</article>
			<?php endfor;?>
			</section>
		</section>
	</div>


	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	</body>
</html>