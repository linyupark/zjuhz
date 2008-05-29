document.write("<script type=\"text/javascript\" src=\"/static/scripts/member/my/my.js\"></script>");
document.write("<script type=\"text/javascript\" src=\"/static/scripts/jquery.ajaxfileupload.js\"></script>");

$(function() {
    $("#btnUpload").click( function() {
		doupload();
		return false;
    });

    $("#fileData").change( function() {
		($("#fileData").val ? $("#btnUpload").attr("disabled", false) : '');
		return false;
    });
});	
	
function doupload() {
	$("#btnUpload").attr("disabled", true);

	$("#loading")
    .ajaxStart(function(){ $(this).show(); })
    .ajaxComplete(function(){ $(this).hide(); });

    $.ajaxFileUpload ({
		url:"/member/my/doface/", 
        secureuri:false, 
        fileElementId:"fileData", 
        dataType: "json", 
        success: function (data, status) { goToUrl("reload", 0); }, 
        error: function (data, status, e) { goToUrl("reload", 0); }
	})

    return false;
}
