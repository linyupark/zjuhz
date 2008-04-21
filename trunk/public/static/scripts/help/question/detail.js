document.write("<script type=\"text/javascript\" src=\"/static/scripts/tinymce/tiny_mce_gzip.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/tinymce/tiny_mce_help.js\"></script>");

$(function() {
	helpRank();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });
	$("#myform").submit( function() {
	    reply_insert();
		return false;
    } );
    $("#btnReply").click( function() {
		reply_insert();
    });
});

// 提交回答
function reply_insert() {
	ajaxloading(true);
	$("#btnReply").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();

	$.ajax( {
		type   : "POST",
        url    : "/help/reply/doinsert/",
        data   : formdata,
	    success: function(msg) {
			if (msg == "reload") {
				window.location.reload();
			}
			else {
				$("#btnReply").attr("disabled", false);
				getVerifyCode();
				ajaxhint(true,msg);
				ajaxloading();
			}

		}
	});

	return false;
}

// 采纳
function question_accept(qid, rid, uid)
{
	var pop = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:80 });
	pop.setContent("title", "采纳为答案");
	pop.setContent("confirmCon", "您确定将此回答采纳为最佳答案吗？");
	pop.setContent("callBack", doaccept);
	pop.setContent("parameter", {qid:qid, rid:rid, uid:uid});
	pop.build();
	pop.show();
	return false;
}
function doaccept(param)
{
	var sort0 = $("#sort0").val();
	var sort1 = $("#sort1").val();
	var sort2 = $("#sort2").val();

	$.post("/help/question/doaccept/", { 
		qid:param["qid"], rid:param["rid"], ruid:param["uid"], sort0:sort0, sort1:sort1, sort2:sort2}, 
			function(msg) { //alert(msg);
				if (msg == 'message') { popup_message("/help/index/message/"); }
	});
}
