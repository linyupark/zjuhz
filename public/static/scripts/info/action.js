//创建分类
function cate_add()
{
	$('.tb').before('<p class="tips">尝试创建中...</p>');
	$.post('/info/category/add/',$('#cate_add').fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(1000);
		}
	);
}

//修改分类名
function cate_mod(cate_id)
{
	$('.tb').before('<p class="tips">尝试修改中...</p>');
	$.post('/info/category/mod/',$('#cate_'+cate_id).fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(1000);
		}
	);
}

//删除分类名
function cate_del(cate_id)
{
	var del_flag = confirm('是否真的要删除该类别?这将影响到相关的信息内容');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/category/del/',$('#cate_'+cate_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该类别已成功删除')
					$('#cate_'+cate_id).fadeOut();
				$('.tips').fadeOut(1000);
			}
		);	
	}
}

//删除信息
function post_del(post_id)
{
	var del_flag = confirm('是否真的要删除该信息？');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/post/del/',$('#post_'+post_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该信息已成功删除')
					$('#post_'+post_id).fadeOut();
				$('.tips').fadeOut(1000);
			}
		);	
	}
}