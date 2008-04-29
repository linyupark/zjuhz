// 修改自己的通讯录
function updateMyClassAddressbook()
{
	$.post('/class/ajax/my_class_addressbook', $('#address_form').fastSerialize(), function(data){
		$.facebox(data);
	});
	return false;
}

// 自己的通讯录
function myClassAddressbook(classid)
{
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:myClassAddressbook"]').addClass('focus');
	$.post('/class/ajax/my_class_addressbook', {class_id:classid}, function(html){
		$('#listInner').html(html);
	});
}

// 查看班级通讯录
function classAddressbookView(classid,pagenum)
{
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:classAddressbookView"]').addClass('focus');
	$.post('/class/ajax/class_addressbook_view', {class_id:classid,page:pagenum}, function(data){
		$('#listInner').html(data);
	});
}

// 提交新话题
function topicNew()
{
	var data = $('#topic_form').fastSerialize();
	data.push({name:'content', value:editor.data()});
	$.post('/class/ajax/class_topic_new', data, function(html)
	{
		$.facebox(html);
	});
	return false;
}

//增加TAG
function strip_tag()
{
	$('.tag_inner').html('');
}
function add_tag()
{
	$('.tag_inner').append(' <input type="text" name="tag[]" size="6" class="inputtxt mu5" />');
}

/** 以后要删除的 -- 站内信(放到memeber) */
function sendMessage(uid)
{
	$.facebox('功能尚未开放');
}

// 开除管理员
function memberLvldown(classid)
{
	var d = confirm('开除选中的管理员吗？');
	if(d == true)
	$.post('/class/ajax/class_member_lvldown', $('.class_list').fastSerialize(), function(data)
	{
		if(data == '') history.go(0);
		else $.facebox(data);
	});
}

// 班级管理员列表
function managerList(classid)
{
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:managerList"]').addClass('focus');
	$.post('/class/ajax/class_manager_list/',{class_id:classid},function(data){
		$('#listInner').html(data);
	});
}

// 踢出班级
function memberOut(classid)
{
	var d = confirm('确定将选中的成员踢出班级吗？');
	if(d == true)
	$.post('/class/ajax/class_member_out', $('.class_list').fastSerialize(), function(data)
	{
		if(data == '') history.go(0);
		else $.facebox(data);
	});
}

// 升级为班级管理员
function memberLvlup(classid)
{
	$.post('/class/ajax/class_member_lvlup', $('.class_list').fastSerialize(), function(data)
	{
		if(data == '') history.go(0);
		else $.facebox(data);
	});
}

// 班级成员列表
function memberList(classid)
{
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:memberList"]').addClass('focus');
	$.post('/class/ajax/class_member_list/',{class_id:classid},function(data){
		$('#listInner').html(data);
	});
}

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
		if(data == '') history.go(0);
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
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:applyList"]').addClass('focus');
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