$(function(){
	$('.s-input-tab-txt').click(function(){
		showTabNav($('#J_TabNav').hasClass('off'));
	});
	$('#s-ul').on("click","li",function(){
		showTabNav(false);
		$('#s-type').val($(this).attr('class'));
		$('.s-input-tab-txt').html($(this).text());
	});
});

function showTabNav(isShow){
	if(isShow){
		$('#J_TabNav').removeClass('off');
		$('#J_TabNav').addClass('on');
	}else{
		$('#J_TabNav').removeClass('on');
		$('#J_TabNav').addClass('off');
	}
}

function toSearch(){
	if('author' == $('#s-type').val()){
		WST.search(3);
	}else if('shop' == $('#s-type').val()){
		WST.search(1);
	}else{
		WST.search(0);
	}
}