//显示新闻TAB
function show_news(num)
{
	$('#news_list a').removeClass('focus');
	$('#num_'+num+' img:visible').hide();
	$('.news_page:visible').hide();
	$('#num_'+num).show();
	$('#num_'+num+' img:hidden').fadeIn('normal');
	$('#news_list a:eq('+(num-1)+')').addClass('focus');
}