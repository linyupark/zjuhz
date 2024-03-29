﻿$(function() {
	helpRank();

	// form
    $("#vcode").focus( function() {
		putVerifyImg();
    });

    $("#append").keypress( function() {
		if (150 <= $("#append").val().length) {
			return false;
		}
    });

	$("#myform").submit( function() {
		reply_insert();
		return false;
    });

	$("#appendform").submit( function() {
		doappend();
		return false;
    });

	$("#appendbtn").click( function() {
		$("#appenddiv").toggle();
		$("#memodiv").toggle();
		return false;
    });
});

// 提交回答
function reply_insert() {
	ajaxloading(true);
	$("#btnReply").attr("disabled", true);
	var formdata = $("#myform").fastSerialize();
    formdata.push({name:'content', value:editor.data()});

	$.post("/help/reply/doinsert/", formdata, function(msg) {
        ajaxloading();
		$("#btnReply").attr("disabled", false);

		if ("reload" == msg) {
			ajaxhint(false);
			window.location.reload();
			return false;
		}

		getVerifyCode();
		ajaxhint(true, msg);
	});

	return false;
}

// 采纳
function question_accept(qid, rid, uid) {
	var pop = new Popup({ contentType:3, isReloadOnClose:false, width:340, height:90 });
	pop.setContent("title", "采纳为最佳答案");
	pop.setContent("confirmCon", "您确定将此回答采纳为最佳答案吗？");
	pop.setContent("callBack", doaccept);
	pop.setContent("parameter", {qid:qid, rid:rid, uid:uid});
	pop.build();
	pop.show();
}

function doaccept(param) {
	var sort0 = $("#sort0").val();
	var sort1 = $("#sort1").val();
	var sort2 = $("#sort2").val();

	$.post("/help/question/doaccept/", { 
		qid:param["qid"], rid:param["rid"], ruid:param["uid"], sort0:sort0, sort1:sort1, sort2:sort2}, 
			function(msg) { //alert(msg);
				if ('message' == msg) { popup_message("/help/index/message/"); }
				goToUrl("reload", 3000);
	});
}

// 补充
function doappend() {
	ajaxloading(true);
	$("#btnAppend").attr("disabled", true);
	var formdata = $("#appendform").fastSerialize();

	$.post("/help/question/doappend/", formdata, function(msg) {
        ajaxloading();
		$("#btnAppend").attr("disabled", false);

		if ("refresh" == msg) {
			ajaxhint(false);
			goToUrl("reload", 0);
		    /*$("#appenddiv").toggle();
            $("#memodiv").toggle();
			$("#memodiv").html("<p>"+$("#append").val()+"</p>");*/
			return false;
		}

	});

	return false;
}