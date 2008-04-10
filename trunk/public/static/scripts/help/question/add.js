$(function() {
	// sort
	sort_init();

	$("#sortParent").change( function() {
		sort_list('sortParent','sortChild');
    });

	$("#sortChild").change( function() {
		sort_list('sortChild','sortGrandSon');
    });


	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#btnSubmit").click( function() {
		question_add();
    });
});

// 提交问题
function question_add() {
	ajaxloading(true);
	$("#btnSubmit").attr("disabled", true);
	var formdata = $("#frmQuestion").fastSerialize();
	$.ajax( {
		type   : "POST",
        url    : "/help/question/doadd/",
        data   : formdata,
	    success: function(msg) {
			$("#btnSubmit").attr("disabled", false);
			ajaxhint(true,msg);
			ajaxloading();
		}
	});

	return false;
}
