<?php
	class CommentModel extends Zend_Db_Table_Abstract 
	{
		protected $_name = 'tbl_comment';
		protected $_primary = 'comment_id';
		
	}