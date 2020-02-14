$(function(){
	var userOnline = Number($('span.user_online').attr('id'));
	var clicou = [];

	function in_array(valor, array){
		for(var i =0; i<array.length;i++){
			if(array[i] == valor){
				return true;
			}
		}

		return false;
	}

	function add_janela(id, nome, status){
		var janelas = Number($('#chats .window').length);
		var pixels = (270+5)*janelas;
		var style = 'float:none; position:absolute; bottom:0; left:'+pixels+'px';

		var splitDados = id.split(':');
		var id_user = Number(splitDados[1]);


		var janela = '<div class="window" id="janela_'+id_user+'" style="'+style+'">';
		janela += '<div class="header_window"><a href="#" class="close">X</a> <span class="name">'+nome+'</span> <span id="'+id_user+'" class="'+status+'"></span></div>';
		janela += '<div class="body"><div class="mensagens"><ul></ul></div>';
		janela += '<div class="send_message" id="'+id+'"><input type="text" name="mensagem" class="msg" id="'+id+'" /></div></div></div>';

		$('#chats').append(janela);
	}



	function retorna_historico(id_conversa){
		$.ajax({
			type: 'POST',
			url: 'sys/historico.php',
			data: {conversacom: id_conversa, online: userOnline},
			dataType: 'json',
			success: function(retorno){
				$.each(retorno, function(i, msg){
					if($('#wndw_'+msg.janela_de).length > 0){
						if(userOnline == msg.id_de){
							$('#wndw_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'" class="eu"><p>'+msg.mensagem+'</p></li>');
						}else{
							$('#wndw_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'"><div class="imgSmall"><img src="fotos/'+msg.fotoUser+'" /></div><p>'+msg.mensagem+'</p></li>');
						}
					}
				});
				[].reverse.call($('#wndw_'+id_conversa+' .mensagens li')).appendTo($('#wndw_'+id_conversa+' .mensagens ul'));
				$('#wndw_'+id_conversa+' .mensagens').animate({scrollTop: 230}, '500');
			}
		});
	}

	$('body').on('click', '#users_online a', function(){
		var id = $(this).attr('id');
		$(this).removeClass('comecar');

		var status = $(this).next().attr('class');
		var splitIds = id.split(':');
		var idJanela = Number(splitIds[1]);
	
		if($('#wndw_'+idJanela).length == 0){
			var nome = $(this).text();
			add_janela(id, nome, status);
			retorna_historico(idJanela);
		}else{
			$(this).removeClass('comecar');
		}
	});

	$('body').on('click', '.header_window', function(){
		var next = $(this).next();
		next.toggle(100);
	});

	$('body').on('click', '.close', function(){
		var parent = $(this).parent().parent();
		var idParent = parent.attr('id');
		var splitParent = idParent.split('_');
		var idJanelaFechada = Number(splitParent[1]);

		var contagem = Number($('.window').length)-1;
		var indice = Number($('.close').index(this));
		var restamAfrente = contagem-indice;

		for(var i = 1; i <= restamAfrente; i++){
			$('.window:eq('+(indice+i)+')').animate({left:"-=275"}, 200);
		}
		parent.remove();
		$('#users_online li#'+idJanelaFechada+' a').addClass('comecar');
	});

	$('body').on('keyup', '.msg', function(e){
		if(e.which == 13){
			var texto = $(this).val();
			var id = $(this).attr('id');
			var split = id.split(':');
			var para = Number(split[1]);

			$.ajax({
				type: 'POST',
				url: 'sys/submit.php',
				data: {mensagem: texto, de: userOnline, para: para},
				success: function(retorno){
					if(retorno == 'ok'){
						$('.msg').val('');
					}else{
						alert("Ocorreu um erro ao enviar a mensagem");
					}
				}
			});
		}
	});

	$('body').on('click', '.mensagens', function(){
		var janela = $(this).parent().parent();
		var janelaId = janela.attr('id');
		var idConversa = janelaId.split('_');
		idConversa = Number(idConversa[1]);

		$.ajax({
			url: 'sys/ler.php',
			type: 'POST',
			data: {ler: 'sim', online: userOnline, user: idConversa},
			success: function(retorno){}
		});
	});

	function verifica(timestamp, lastid, user){
		var t;
		$.ajax({
			url: 'sys/stream.php',
			type: 'GET',
			data: 'timestamp='+timestamp+'&lastid='+lastid+'&user='+user,
			dataType: 'json',
			success: function(retorno){
				clearInterval(t);
				if(retorno.status == 'resultados' || retorno.status == 'vazio'){
					t =setTimeout(function(){
						verifica(retorno.timestamp, retorno.lastid, userOnline);
					},1000);

					if(retorno.status == 'resultados'){
						$.each(retorno.dados, function(i, msg){
							if(msg.id_para == userOnline){
								$.playSound('sys/effect');
							}

							if($('#wndw_'+msg.janela_de).length == 0){
								$('#users_online #'+msg.janela_de+' .comecar').click();
								clicou.push(msg.janela_de);
							}

							if(!in_array(msg.janela_de, clicou)){
								if($('.mensagens ul li#'+msg.id).length == 0 && msg.janela_de > 0){
									if(userOnline == msg.id_de){
										$('#wndw_'+msg.janela_de+' .mensagens ul').append('<li class="eu" id="'+msg.id+'"><p>'+msg.mensagem+'</p></li>');
									}else{
										$('#wndw_'+msg.janela_de+' .mensagens ul').append('<li id="'+msg.id+'"><div class="imgSmall"><img src="imgs/picture/'+msg.fotoUser+'" border="0"/></div><p>'+msg.mensagem+'</p></li>');
									}
								}
							}
						});
						$('.mensagens').animate({scrollTop: 230}, '500');
						console.log(clicou);
					}
					clicou = [];
					$('#users_online ul').html('');
					$.each(retorno.users, function(i, user){
						var incluir = '<li id="'+user.id+'"><div class="imgSmall"><img src="imgs/picture/'+user.foto+'" border="0"/></div>';
						incluir += '<a href="#" id="'+userOnline+':'+user.id+'" class="comecar">'+user.nome+'</a>';
						incluir += '<span id="'+user.id+'" class="status '+user.status+'"></span></li>';
						$('span#'+user.id).attr('class', 'status '+user.status);
						$('#users_online ul').append(incluir);
					});
				}else if(retorno.status == 'erro'){
					alert('Ficamos confusos, atualize a pagina');
				}
			},
			error: function(){
				clearInterval(t);
				t=setTimeout(function(){
					verifica(retorno.timestamp, retorno.lastid, userOnline);
				},15000);
			}
		});
	}

	verifica(0,0,userOnline);
});