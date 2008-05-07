// Sort相关初始
function sort_init()
{
	$("#sort0").change( function() {
		sort_list("sort0");
    });

	$("#sort1").change( function() {
		sort_list("sort1");
    });

	//$("#sort2").change( function() {
	//	sort_list("sort2");
    //});

	$("select").attr({ size: "8", style: "width:150px" }); // select?
	sort_add_options("sort0", 0);
}

// Sort多级菜单
function sort_list(getname)
{
    var id   = getname.replace(/[^0-9]+/ig, "");
    var name = getname.replace(/[^a-z]+/ig, "");
	var next = parseInt(id) + 1;

	var cnt  = $("select").length; // select?
	var i;
	for(i=next; i<cnt; i++) {
		sort_reset(name+i);
	}

	// 获取选中值
	var sid  = $("#" + getname).val();
	// 写入
	$("#" + name + "Id").val(sid);

	if (sid > 1 && next < cnt) {
		$.getJSON("/help/sort/json/", { sid: sid }, function(msg) {
				if (msg != "") {
					$("#" + name + next).addOption('', '请选择');
					$("#" + name + next).addOption(msg, false);
				}
			}
		);
	}
}

// Sort菜单重置
function sort_reset(name)
{
	document.getElementById(name).options.length = 0;
}

// Sort选项初始
function sort_add_options(name, sid)
{
	$.getJSON("/help/sort/json/", { parent: sid }, function(msg) { $("#" + name).addOption(msg, false); } );
}
