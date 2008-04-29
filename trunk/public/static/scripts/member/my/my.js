$(function() {
	$(".control_link a").removeClass("focus");
	$('.control_link a[href^="/member/my/'+ctrl+'/"]').addClass('focus');
	$(".control_tab a").removeClass("focus");
	$('.control_tab a[href="/member/my/'+ctrl+'/type/'+type+'/"]').addClass('focus');
});
