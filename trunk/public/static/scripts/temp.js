function showTab(num)
{
	$('#help_tab_btn a').css({borderColor:'#C4DBBF',backgroundColor:'#fff',color:'#5C7B23'});
	$('#help_tab_btn a:eq('+num+')').css({borderColor:'#FAD1A5',backgroundColor:'#FFFCF0',color:'#C26400'});
	$('.tab_content').hide();
	$('.tab_content:eq('+num+')').show();
}

function cardInit()
{
	$('a[rel="card"]').bind('mouseover',function(){
		$('.cardContainer').remove();
		var x = $(this).offset().left;
		var y = $(this).offset().top;
		var w = $('body').width();
		var h = $('body').height();
		var card_w = 300;
		$(this).after('<div class="cardContainer"></div>');
		$('.cardContainer').css({position:'absolute', width:card_w, top:y, opacity: 0.8});
		if((card_w+x) < w)
		{
			$('.cardContainer').css({left:x, background:'#f5f5f5 url(/static/images/bg/card_left.gif) no-repeat left top'});
		}
		else
		{
			x = x-card_w;
			$('.cardContainer').css({left:x, background:'#f5f5f5 url(/static/images/bg/card_right.gif) no-repeat right top'});
		}
		$('.cardContainer').load('/temp/card/load');
		$('.cardContainer').mouseout(function(){$(this).remove()});
	});
}
