// Sort��س�ʼ
function sort_init()
{
	$("#sort0").change( function() {
		sort_list('sort0');
    });

	$("#sort1").change( function() {
		sort_list('sort1');
    });

	$("#sort2").change( function() {
		sort_list('sort2');
    });

	$("select").attr({ size: "10",style: "width:150px" }); // select?
	sort_add_options('sort0',0);
}

// Sort�༶�˵�
function sort_list(getname)
{
    var id   = getname.replace(/[^0-9]+/ig,"");
    var name = getname.replace(/[^a-z]+/ig,"");
	var next = parseInt(id) + 1;

	var cnt  = $("select").length; // select?
	var i;
	for(i=next; i<cnt; i++) {
		sort_reset(name+i);
	}

	// ��ȡѡ��ֵ
	var sid  = $("#" + getname).val();
	// д��
	$("#" + name + 'Id').val(sid);

	if (sid > 1 && next < cnt) {
		$.getJSON("/help/sort/json/", { sid: sid }, function(msg) {
				if (msg != "") {
					$("#" + name + next).addOption(msg, false);
				}
			}
		);
	}
}

// Sort�˵�����
function sort_reset(name)
{
	document.getElementById(name).options.length = 0;
}

// Sortѡ���ʼ
function sort_add_options(name,sid)
{
	$.getJSON("/help/sort/json/", { parent: sid }, function(msg) { $("#" + name).addOption(msg, false); } );
}
