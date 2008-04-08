//增加TAG
function add_tag()
{
	$('.tag_inner').append('<input type="text" name="tag[]" size="6" />');
}

//修改权限
function role_mod(user_id)
{
	$('.tb').before('<p class="tips">尝试修改中...</p>');
	$.post('/info/role/mod/',$('#role_'+user_id).fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(2000);
		}
	);
}

//删除后台角色
function role_del(user_id)
{
	var del_flag = confirm('是否真的要删除该帐号？这将影响到其发布的一些信息');
	if(del_flag == true)
	{
		$('.tb').before('<p class="tips">尝试删除中...</p>');
		$.post('/info/role/del/',$('#role_'+user_id).fastSerialize(),
			function(data){
				$('.tips').html(data);
				if(data == '该成员帐号删除成功！')
					$('#role_'+user_id).fadeOut(1000);
				$('.tips').fadeOut(3000);
			}
		);	
	}
}

//创建角色帐号
function role_add()
{
	$('.tb').before('<p class="tips">尝试创建中...</p>');
	$.post('/info/role/add/',$('#new_role').fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(3000);
		}
	);
}

//创建分类
function cate_add()
{
	$('.tb').before('<p class="tips">尝试创建中...</p>');
	$.post('/info/category/add/',$('#cate_add').fastSerialize(),
		function(data){
			$('.tips').html(data);
			$('.tips').fadeOut(3000);
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
			$('.tips').fadeOut(3000);
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
					$('#cate_'+cate_id).fadeOut(1000);
				$('.tips').fadeOut(3000);
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
					$('#post_'+post_id).fadeOut(1000);
				$('.tips').fadeOut(3000);
			}
		);	
	}
}