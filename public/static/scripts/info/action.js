// 专题图片分页显示缩略图
function album(name,page)
{
	$.get('/info/subject/album/of/'+name+'/p/'+page,null,function(html){
		$('.album').html(html);
	});
}

// 选择刷新缓存的区域
function cacheFlush(cache_name)
{
	$.post('/info/admin/manager_cache', {cache_name:cache_name}, function(html){
		if(html == '') $.facebox('刷新完毕！');
		else $.facebox(html);
	});
}

//分类图标选择完毕
function icon_selected(cid, icon)
{
	$('#c'+cid).val(icon);
	$.facebox.close();
}	

//增加TAG
function add_tag()
{
	$('.tag_inner').append('<input type="text" name="tag[]" size="6" />');
}

//管理成员修改
function manager_mod(user_id)
{
	$('.tb').before('<p class="tips">尝试修改中...</p>');
	$.post('/info/admin/manager_mod',$('#role_'+user_id).fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(1000);
		}
	);
}

//删除后台角色
function manager_del(user_id)
{
	var del_flag = confirm('是否真的要删除该帐号？这将同时删除其所相关的文章');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/admin/manager_del',$('#role_'+user_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该成员帐号删除成功！')
					$('#role_'+user_id).fadeOut(1000);
				$('.tips').fadeOut(1000);
			}
		);	
	}
}

// 信息发布
function entity_pub(post_id)
{
	$('.tb').before('<p class="tips">尝试发布中...</p>');
		$.post('/info/admin/entity_pub',$('#post_'+post_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该信息已成功发布')
					$('#post_'+post_id).fadeOut(1000);
				$('.tips').fadeOut(1000);
			}
		);	
}

//修改分类名
function cate_mod(cate_id)
{
	$('.tb').before('<p class="tips">尝试修改中...</p>');
	$.post('/info/admin/category_mod',$('#cate_'+cate_id).fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(3000);
		}
	);
}

//删除分类名
function cate_del(cate_id)
{
	var del_flag = confirm('是否真的要删除该类别?在此分类的所有文章都会被删除');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/admin/category_del',$('#cate_'+cate_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该类别以及相关信息已成功删除')
					$('#cate_'+cate_id).fadeOut(1000);
				$('.tips').fadeOut(1000);
			}
		);	
	}
}

//删除信息
function entity_del(post_id)
{
	var del_flag = confirm('是否真的要删除该信息？');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/admin/entity_del',$('#post_'+post_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该信息已成功删除')
					$('#post_'+post_id).fadeOut(1000);
				$('.tips').fadeOut(1000);
			}
		);	
	}
}