function search_focus(class_name)
{
	$('.'+class_name+' input').attr('value','');
	$('.'+class_name+' input').css('background','#ffc');
	$('.'+class_name+' input').css('color','#333');
}

function search_blur(class_name,input_value)
{
	$('.'+class_name+' input').attr('value',input_value);
	$('.'+class_name+' input').css('background','#fff');
	$('.'+class_name+' input').css('color','#aaa');
}


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