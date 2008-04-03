function show_tab(num)
{
	$('#help_tab_btn a').css({borderColor:'#C4DBBF',backgroundColor:'#fff',color:'#5C7B23',borderBottomColor:'#fff'});
	$('#help_tab_btn a:eq('+num+')').css({borderColor:'#FAD1A5',backgroundColor:'#FFFCF0',color:'#C26400',borderBottomColor:'#fff'});
	$('.tab_content').hide();
	$('.tab_content:eq('+num+')').show();
}