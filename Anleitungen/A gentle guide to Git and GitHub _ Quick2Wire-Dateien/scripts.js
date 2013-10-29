var $pc = jQuery.noConflict();

$pc(function(){
	$pc('ul .sub-menu').prepend('<li class="arrow-up"></li>');
	$pc('nav ul .children').prepend('<li class="arrow-up"></li>');
	
	$pc('ul.sub-menu li').parent().mouseenter(function(){
		$pc(this).parent().addClass('menuhovered');
	});
	$pc('nav ul.children li').parent().mouseenter(function(){
		$pc(this).parent().addClass('menuhovered');
	});
	
	$pc('ul.sub-menu li').parent().mouseleave(function(){
		$pc(this).parent().removeClass('menuhovered');
	});
	$pc('nav ul.children li').parent().mouseleave(function(){
		$pc(this).parent().removeClass('menuhovered');
	});
		
	$pc('button[id=showmenu]').click(function(){
		$pc('nav').slideToggle(400);
	});
});

