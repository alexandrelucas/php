/// <reference path="../../typings/globals/jquery/index.d.ts" />
$(function(){
    $('body').on('click', '.imgSmall', function(e){
    	e.stopPropagation();
       $('.settings').toggleClass('hidden');
    });
   	 $(document).click(function(){
    	if(!$('.settings').hasClass('hidden'))
    		$('.settings').addClass('hidden');
    });
    $('.settings').click(function(e){
    	e.stopPropagation();
    });
});