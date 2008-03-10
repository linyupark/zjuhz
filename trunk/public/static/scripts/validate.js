// 登录帐号
function chkUsername(username)
{
	//字母 数字 下划线 3-16个字符 字母开头
	var pattern = /^[a-zA-Z][a-zA-Z0-9_]{1,14}[a-zA-Z0-9]$/i;
	if (username.length >= 3 && username.length <= 16 && pattern.test(username))
	{
		return true;
	}
	
	return false;
}

// 用户密码
function chkPasswd(passwd)
{
	//6-16位
	if (passwd.length >= 6 && passwd.length <= 16)
	{
		return true;
	}
	
	return false;
}

// 真实姓名
function chkRealname(realname)
{
	//6-16位中英日韩，不能有数字和符号，不能既有中又有英等类似
    var pattern = /^[\u2E80-\u9FFF\s]+$|^[a-zA-Z\s]+$/i;
	
    if(realname.length < 2 || realname.length > 16 || !pattern.test(realname.replace(/^\s+|\s+$/g,"")))
	{
        return false;
    }
	
	return true;
}

// 电子邮箱
function chkEmail(email)
{
	//6-50位电子邮箱
    var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/i;
	
    if(email.length < 6 || email.length > 50 || !pattern.test(email))
	{
        return false;
    }
	
	return true;
}
