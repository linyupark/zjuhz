document.write("<script type=\"text/javascript\" src=\"/static/scripts/jquery.ajaxfileupload.js\"></script>");

$(function() {
    $("#frmBasic").submit( function() {
		dobasic();
		return false;
    });

    $("#frmContact").submit( function() {
		docontact();
		return false;
    });

    $("#frmBiz").submit( function() {
		dobiz();
		return false;
    });

	$("#btnLogo").click( function() {
		dologo();
		return false;
    });

    $("#fileData").change( function() {
		($("#fileData").val ? $("#btnLogo").attr("disabled", false) : '');
		return false;
    });
});

// 基础信息
function dobasic() {
	ajaxloading(true);
	$("#btnBasic").attr("disabled", true);
	var formdata = $("#frmBasic").fastSerialize();
	//formdata.push({name:'content', value:editor.data()});

	$.post("/biz/manage/dobasic/", formdata, function(msg) {
		ajaxloading();
        $("#btnBasic").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/biz/index/message/");
			goToUrl("reload", 1000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}

// 联系方式
function docontact() {
	ajaxloading(true);
	$("#btnContact").attr("disabled", true);
	var formdata = $("#frmContact").fastSerialize();

	$.post("/biz/manage/docontact/", formdata, function(msg) {
		ajaxloading();
        $("#btnContact").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/biz/index/message/");
			goToUrl("reload", 1000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}

// 商务供求
function dobiz() {
	ajaxloading(true);
	$("#btnBiz").attr("disabled", true);
	var formdata = $("#frmBiz").fastSerialize();

	$.post("/biz/manage/dobiz/", formdata, function(msg) {
		ajaxloading();
        $("#btnBiz").attr("disabled", false);

		if ("message" == msg) {
			ajaxhint(false);
			popup_message("/biz/index/message/");
			goToUrl("reload", 1000);
			return false;
		}

		ajaxhint(true, msg);
	});

	return false;
}

// 企业标志
function dologo() {
	$("#btnLogo").attr("disabled", true);

    $("#loading")
    .ajaxStart(function(){ $(this).show(); })
    .ajaxComplete(function(){ $(this).hide(); });

    $.ajaxFileUpload ({
		url: "/biz/manage/dologo/", 
        secureuri: false, 
        fileElementId: "fileData", 
        dataType: "json", 
        success: function (data, status) { goToUrl("reload", 0); }, 
        error: function (data, status, e) { goToUrl("reload", 0); }
    })

    return false;
}
