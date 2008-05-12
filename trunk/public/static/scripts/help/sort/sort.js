$(function() {
	$('#help_tab_btn a').removeClass('focus');
	$('#help_tab_btn a[href^="/help/sort/browse/type/'+type+'/sid/"]').addClass('on');
	helpRank();
});
