<?php

	class CategoryModel extends Zend_Db_Table_Abstract 
	{
		protected $_name = 'tbl_category';
		protected $_primary = 'category_id';
		
		#根据当前的角色权限获取相应目录
		function fetchByPower()
		{
			/*
			$sess = Zend_Registry::get('sessCommon');
			if($sess->role == 'admin')
			{
				return $this->fetchAll();
			}
			else 
			{
				$power_arr = explode('|', Zend_Registry::get('sessInfo')->power); //权力数组
				return $this->find($power_arr);
			}
			*/
			return $this->fetchAll();
		}
	}