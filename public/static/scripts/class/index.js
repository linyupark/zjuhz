// 删除申请
function applyDel(classid)
{
	var d = confirm('确定要删除选中的申请吗？');
	if(d == true)
	$.post('/class/ajax/class_apply_del', $('.class_list').fastSerialize(), function(data)
	{
		applyList(classid);
	});
}

// 批准申请
function applyPass(classid)
{
	$.post('/class/ajax/class_apply_pass', $('.class_list').fastSerialize(), function(data)
	{
		if(data == '') applyList(classid);
		else $.facebox(data);
	});
}

// 全选
function unselectAll(name)
{
	$('input[name^='+name+']').attr('checked',false);
	$('a[href^="javascript:unselectAll"]').attr('href',"javascript:selectAll('"+name+"')");
}
function selectAll(name)
{
	$('input[name^='+name+']').attr('checked',true);
	$('a[href^="javascript:selectAll"]').attr('href',"javascript:unselectAll('"+name+"')");
}

// 申请列表
function applyList(classid)
{
	$('a[href^="javascript:applyList"]').css({color:'#111',fontWeight:'bold'});
	$.post('/class/ajax/class_apply_list/',{class_id:classid},function(data){
		$('#listInner').html(data);
	});
}

// 显示申请内容
function applyDetail(classid,applyid)
{
	$.post('/class/ajax/class_apply_detail/',{class_id:classid,apply_id:applyid},function(data){
		$.facebox(data);
	});
}

// 显示申请表
function classJoin(classid)
{
	$.post('/class/ajax/join/',{class_id:classid},function(data){
		$.facebox(data);
	});
}

// 提交申请表
function classJoinApply(classid)
{	var content = $('#class_apply_content').val();
	$.post('/class/ajax/join_apply/',{class_id:classid,apply_content:content},function(data){
		$('#apply_result').html(data);
	});
}

// 修改班级黑板报
function modClassNotice(classid,status)
{
	if(status == 0)
	{
		$.post('/class/ajax/class_notice_fetch/',{class_id:classid},function(data){
			$.facebox(data);
		});
	}
	else
	{
		var content = $('#class_notice_content').val();
		$.post('/class/ajax/class_notice_mod/',{class_id:classid,notice_content:content},function(){
			content = content.replace(/\n/gi,'<br />');
			$('#class_notice').html(content);
			$('#facebox .close').click();
		});
	}
}