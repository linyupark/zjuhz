$(function() {
	$('.control_link a').removeClass('focus');
	$('.control_link a[href^="/help/my/'+ctrl+'/"]').addClass('focus');
	$('.control_tab a').removeClass('focus');
	$('.control_tab a[href="/help/my/'+ctrl+'/type/'+type+'/"]').addClass('focus');
});