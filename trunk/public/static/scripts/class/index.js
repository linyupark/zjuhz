function tabFocus(name)
{
	$('a[href^="javascript:"]').removeClass();
	$('#listInner').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$('a[href^="javascript:'+name+'"]').addClass('focus');
}

// --------------------------- 邀请加入班级相关 ------------------------

// 接受邀请
function inviteAccept(invite_id, class_id, uid)
{
	var data = {invite_id:invite_id, class_id:class_id, member_id:uid};
	$.post('/class/ajax/invite_accept',data,function(html){
		$.facebox(html);
	});
}

// 拒绝邀请
function inviteRefuse(invite_id)
{
	var data = {invite_id:invite_id};
	$.post('/class/ajax/invite_refuse',data,function(html){
		$.facebox(html);
	});
}

// 查看邀请
function inviteView(invite_id)
{
	var data = {invite_id:invite_id};
	$.post('/class/ajax/invite_view',data,function(html){
		$.facebox(html);
	});
}

// 发送邀请
function inviteSend()
{
	$('#innerHTML').html('<img src="/static/images/icon/ajax-loader.gif" /> 发送中..');
	$.post('/class/ajax/invite_send',$('#invite_send_form').fastSerialize(),function(html){
		$('#innerHTML').html(html);
	});
}

// 邀请的弹框
function invite(uid,class_id)
{
	var data = {uid:uid, class_id:class_id};
	$.post('/class/ajax/invite', data, function(html){
		$.facebox(html);
	});
}

// 可被邀请查找
function inviteSearch()
{
	$('#userList').html('<img src="/static/images/icon/ajax-loader.gif" />');
	$.post('/class/ajax/invite_search',$('#invite_form').fastSerialize(),function(html){
		$('#userList').html(html);
	});
	return false;
}

// --------------------------- 通讯录相关 -------------------------------

// 通讯录导出
function classAddressbookExport(classid)
{
	tabFocus('classAddressbookExport');
	$.post('/class/ajax/addressbook_export',{class_id:classid},function(html){
		$('#listInner').html(html);
	});
}

// 班级通讯录导入
function classAddressbookImport(classid,step)
{
	if(step == 1) // 显示导入页
	{
		tabFocus('classAddressbookImport');
		$.post('/class/ajax/my_personal_addressbook',{class_id:classid},function(html){
			$('#listInner').html(html);
		});
	}
	if(step == 2) // 执行导入工作
	{
		var data = $('#address_records').fastSerialize();
		data.push({name:'class_id',value:classid});
		$.facebox('<img src="/static/images/icon/ajax-loader.gif" /> 数据导入中...');
		$.post('/class/ajax/import_addressbook',data,function(html){
			$.facebox(html);
		});
	}
}

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
	tabFocus('myClassAddressbook');
	$.post('/class/ajax/my_class_addressbook', {class_id:classid}, function(html){
		$('#listInner').html(html);
	});
}

// 查看班级通讯录
function classAddressbookView(classid,pagenum)
{
	tabFocus('classAddressbookView');
	$.post('/class/ajax/class_addressbook_view', {class_id:classid,page:pagenum}, function(data){
		$('#listInner').html(data);
	});
}

// ---------------------- 主话题操作块 ---------------------
function delReply(classid,topic_id,reply_id)
{
	var d = confirm('确定删除该回复吗？');
	if(d == true)
	$.post('/class/ajax/del_reply', { class_id:classid,topic_id:topic_id,reply_id:reply_id } ,function(data)
	{
		$.facebox(data);
	});
}

function delTopic(classid,topic_id)
{
	var d = confirm('确定删除该话题吗？');
	if(d == true)
	$.post('/class/ajax/del_topic', { class_id:classid,topic_id:topic_id } ,function(data)
	{
		$.facebox(data);
	});
}

function eliteTopic(classid,topic_id,is_elite)
{
	$.post('/class/ajax/elite_topic', { class_id:classid,topic_id:topic_id,is_elite:is_elite } ,function(data)
	{
		if(is_elite == 0) is_elite = 1;
		else is_elite = 0;
		$('a[href^="javascript:eliteTopic"]').attr('href','javascript:eliteTopic('+classid+','+topic_id+','+is_elite+')');
		$.facebox(data);
	});
}

function fixTopic(classid,topic_id,is_up)
{
	$.post('/class/ajax/fix_topic', { class_id:classid,topic_id:topic_id,is_up:is_up } ,function(data)
	{
		if(is_up == 0) is_up = 1;
		else is_up = 0;
		$('a[href^="javascript:fixTopic"]').attr('href','javascript:fixTopic('+classid+','+topic_id+','+is_up+')');
		$.facebox(data);
	});
}



// 回复话题框
function postTopicReply(classid,topic_id,page)
{
	$.post('/class/ajax/post_topic_reply', $('#reply_form').fastSerialize(), function(data){
		if(data != '') // 出错
		$('#result').html(data);
		else
		{
			location.reload();
		}
	});
	return false;
}

// 话题回复表单
function topicReplyForm(classid,topic_id,page)
{
	$.post('/class/ajax/topic_reply_form',{class_id:classid,tid:topic_id,p:page}, function(data){
		$.facebox(data);
	});
}

// 获取话题回复数据
function fetchTopicReply(classid,topic_id,page)
{
	$.post('/class/ajax/fetch_topic_reply',{class_id:classid,tid:topic_id,p:page}, function(data){
		$('#innerHtml').html(data);
	});
}

// 更新话题
function topicUpdate()
{
	var data = $('#topic_form').fastSerialize();
	data.push({name:'content', value:editor.data()});
	$.post('/class/ajax/class_topic_mod', data, function(html)
	{
		$.facebox(html);
	});
	return false;
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
	tabFocus('managerList');
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
	tabFocus('memberList');
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
	tabFocus('applyList');
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