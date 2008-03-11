<?php

	class UserModel extends Zend_Db_Table_Abstract 
	{
		protected $_name = 'tbl_user';
		protected $_primary = 'user_id';
	}