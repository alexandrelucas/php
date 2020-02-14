$(function(){

	function limitaCaracteres(textarea, counter, limit){
		$('.'+counter).text(limit+' restantes');
		var left;
		$('.'+textarea).on('keyup', function(e){
			var qtdCaracteres = $(this).val().length;
			left = limit-qtdCaracteres;
			if(left <= 0){
				left = 0;
				var textoAtual = $(this).val();
				var novoTexto = '';
				for(var n = 0; n < limit; n++){
					novoTexto += textoAtual[n];
				}
				$(this).val(novoTexto);
			}
			$('.'+counter).text(left+' restantes');
		});
	}
	limitaCaracteres('msg', 'counter', 140);
});