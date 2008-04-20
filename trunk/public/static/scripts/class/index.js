function classJoin(classid)
{
	$.post('/class/ajax/join/',{class_id:classid},function(data){
		$.facebox(data);
	});
}

function classJoinApply(classid)
{	var content = $('#class_apply_content').val();
	$.post('/class/ajax/join_apply/',{class_id:classid,apply_content:content},function(data){
		$('#apply_result').html(data);
	});
}

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