/*Bouton scroll*/

$(function() {

	$('<div></div>')
			.attr('id','scrolltotop')
			.hide()
			.css({'z-index':'1000','position':'fixed','bottom':'25px','right':'35px','cursor':'pointer','width':'40px','height':'40px','background':'#111111'})
			.appendTo('body')
			.click(function(){
				$('html,body').animate({scrollTop: 0}, 'slow');
			});
		$('<div></div>')
			.css({'width':'6px','height':'6px','transform':'rotate(-135deg)','border':'solid #ffffff','border-width':'0 3px 3px 0','padding':'3px','margin-top':'16px','margin-left':'12px'})
			.appendTo('#scrolltotop');
		$(window).scroll(function(){
			if($(window).scrollTop()<500){
				$('#scrolltotop').fadeOut();
			}else{
				$('#scrolltotop').fadeIn();
			}
		})

	$('<div></div>')
		.attr('id','scrolltobottom')
	
		.css({'z-index':'1000','position':'fixed','bottom':'25px','right':'35px','cursor':'pointer','width':'40px','height':'40px','background':'#111111'})
		.appendTo('body')
		.click(function(){
			$('html,body').animate({scrollTop: 500}, 'slow');
		});
		$('<div></div>')
			.css({'width':'6px','height':'6px','transform':'rotate(-315deg)','border':'solid #ffffff','border-width':'0 3px 3px 0','padding':'3px','margin-top':'10px','margin-left':'12px'})
			.appendTo('#scrolltobottom');
		$(window).scroll(function(){
			if($(window).scrollTop()<500){
				$('#scrolltobottom').fadeIn();
			}else{
				$('#scrolltobottom').fadeOut();
			}
		})
    
})
