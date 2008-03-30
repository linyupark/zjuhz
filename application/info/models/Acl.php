<?php

	class Acl
	{
		static function roleCheck($power)
		{
			$sess = Zend_Registry::get('sess_info');
			$acl = Zend_Registry::get('acl_info');
			//是否拥有$power权限
			if(!$acl->isAllowed($sess->role, null, $power)) 
			{
				Zend_Session::destroy();
				header('Location: /info/console/login/');
			}
		}
	}