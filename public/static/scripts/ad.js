var imgWidth=267;              //图片宽
var imgHeight=190;             //图片高
var textFromHeight=21;         //焦点字框高度 (单位为px)
var textStyle="texttitle";           //焦点字class style (不是连接class)
var textLinkStyle="texttitle"; //焦点字连接class style
var buttonLineOn="#f60";           //button下划线on的颜色
var buttonLineOff="#000";          //button下划线off的颜色
var TimeOut=5000;              //每张图切换时间 (单位毫秒);
var imgUrl=new Array(); 
var imgLink=new Array();
var imgtext=new Array();
var imgAlt=new Array();
var adNum=0;
var theTimer;
//焦点字框高度样式表 开始
document.write('<style type="text/css">');
document.write('#focuseFrom{width:'+(imgWidth+2)+';margin: 0px; padding:0px;height:'+(imgHeight+textFromHeight)+'px; overflow:hidden;}');
document.write('#txtFrom{height:'+textFromHeight+'px;line-height:'+textFromHeight+'px;width:'+imgWidth+'px;overflow:hidden;}');
document.write('#imgTitle{width:'+imgWidth+';top:-'+(textFromHeight+14)+'px;height:18px}');
document.write('</style>');
document.write('<div id="focuseFrom">');
//焦点字框高度样式表 结束
imgUrl[1]='static/editor/sina/uploadfile/200901021230834085.jpg';
imgtext[1]='第二届“大学之声”浙江大学新年音乐会圆满举行';
imgLink[1]='http://www.zjuhz.com/info/view/detail/id/1112';
imgAlt[1]='第二届“大学之声”浙江大学新年音乐会圆满举行';
imgUrl[2]='/static/editor/sina/uploadfile/200811151226679136.jpg';
imgtext[2]='本会代表参加2008走访校友行总结会';
imgLink[2]='http://www.zjuhz.com/info/view/detail/id/923';
imgAlt[2]='本会代表参加2008走访校友行总结会';
imgUrl[3]='/static/groups/4/images/200811121226453688718.jpg';
imgtext[3]='我会派代表参加2008黄山浙大全球校友大会';
imgLink[3]='http://www.zjuhz.com/info/view/detail/id/931';
imgAlt[3]='我会派代表参加2008黄山浙大全球校友大会';
imgUrl[4]='/static/editor/sina/uploadfile/200811011225470094.jpg';
imgtext[4]='企业家经理人同学会徽商故里行成功举办';
imgLink[4]='/info/view/detail/id/849';
imgAlt[4]='企业家经理人同学会徽商故里行成功举办';
imgUrl[5]='/static/editor/sina/uploadfile/200809221222074830.jpg';
imgtext[5]='杭州浙江大学校友会成立大会成功举行';
imgLink[5]='/info/view/detail/id/673';
imgAlt[5]='杭州浙江大学校友会成立大会成功举行';
imgUrl[6]='/static/editor/sina/uploadfile/200809261222444618.jpg';
imgtext[6]='杭州浙江大学校友会首次理事沟通会在郭庄举行';
imgLink[6]='/info/view/detail/id/610';
imgAlt[6]='杭州浙江大学校友会首次理事沟通会在郭庄举行';

function changeimg(n)
{
adNum=n;
window.clearInterval(theTimer);
adNum=adNum-1;
nextAd();
}
function goUrl(){
window.open(imgLink[adNum],'_blank');
}
//NetScape开始
if (navigator.appName == "Netscape")
{
document.write('<style type="text/css">');
document.write('.buttonDiv{height:4px;width:21px;}');
document.write('</style>');
function nextAd(){
if(adNum<(imgUrl.length-1))adNum++;
else adNum=1;
theTimer=setTimeout("nextAd()", TimeOut);
document.images.imgInit.src=imgUrl[adNum];
document.images.imgInit.alt=imgAlt[adNum];	
document.getElementById('focustext').innerHTML=imgtext[adNum];
document.getElementById('imgLink').href=imgLink[adNum];
}
document.write('<a id="imgLink" href="'+imgLink[1]+'" target=_blank class="p1"><img src="'+imgUrl[1]+'" name="imgInit" width='+imgWidth+' height='+imgHeight+' border=1 alt="'+imgAlt[1]+'" class="imgClass"></a><div id="txtFrom"><span id="focustext" class="'+textStyle+'">'+imgtext[1]+'</span></div>')
document.write('<div id="imgTitle">');
document.write('<div id="imgTitle_down">');
//数字按钮代码开始
for(var i=1;i<imgUrl.length;i++){document.write('<a href="javascript:changeimg('+i+')" class="button" style="cursor:pointer" title="'+imgAlt[i]+'">'+i+'</a>');}
//数字按钮代码结束
document.write('</div>');
document.write('</div>');
document.write('</div>');
nextAd();
}
//NetScape结束
//IE开始
else
{
var count=0;
for (i=1;i<imgUrl.length;i++) {
if( (imgUrl[i]!="") && (imgLink[i]!="")&&(imgtext[i]!="")&&(imgAlt[i]!="") ) {
count++;
} else {
break;
}
}
function playTran(){
if (document.all)
imgInit.filters.revealTrans.play();		
}
var key=0;
function nextAd(){
if(adNum<count)adNum++ ;
else adNum=1;
if( key==0 ){
key=1;
} else if (document.all){
imgInit.filters.revealTrans.Transition=23;
imgInit.filters.revealTrans.apply();
playTran();
}
document.images.imgInit.src=imgUrl[adNum];
document.images.imgInit.alt=imgAlt[adNum];	
document.getElementById('link'+adNum).style.background=buttonLineOn;
for (var i=1;i<=count;i++)
{
if (i!=adNum){document.getElementById('link'+i).style.background=buttonLineOff;}
}	
focustext.innerHTML=imgtext[adNum];
theTimer=setTimeout("nextAd()", TimeOut);
}
document.write('<a target=_self href="javascript:goUrl()"><img style="FILTER: revealTrans(duration=1,transition=5);" src="javascript:nextAd()" width='+imgWidth+' height='+imgHeight+' border=0 vspace="0" name=imgInit class="imgClass"></a><br>');
document.write('<div id="txtFrom"><span id="focustext" class="'+textStyle+'"></span></div>');
document.write('<div id="imgTitle">');
document.write(' <div id="imgTitle_down"> <a class="trans"></a>');
//数字按钮代码开始
for(var i=1;i<imgUrl.length;i++){document.write('<a id="link'+i+'"  href="javascript:changeimg('+i+')" class="button" style="cursor:hand" title="'+imgAlt[i]+'" onFocus="this.blur()">'+i+'</a>');}
//数字按钮代码结束
document.write('</div>');
document.write('</div>');
document.write('</div>');
document.write('</div>');
}

//IE结束