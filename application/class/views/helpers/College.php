<?php

	class Zend_View_Helper_College
	{
		function college($id = '')
		{
			$name = Zend_Registry::get('iniConfig')->college->name;
			if($id != '')
			{
				return $name->$id;
			}
			return $name->toArray();
		}
	}