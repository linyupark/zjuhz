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