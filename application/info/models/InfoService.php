<?php 

	/**
	 * info数据库接口类
	 *
	 */
	class InfoService
	{
		private $_db;
		
		function __construct()
		{
			$this->_db = Zend_Registry::get('dbInfo');
		}
		
		/**
		 * 返回需要在首页显示的目录列 pub = 1
		 *
		 * @return array
		 */
		function get_public_categories()
		{
			return $this->_db->fetchAll('SELECT * FROM `tbl_category` WHERE `category_pub` = 1');
		}
		
		/**
		 * 获取信息列表,根据目录id
		 *
		 * @param integer $cat_id
		 * @return array
		 */
		function get_arts_snapshot($cat_id)
		{
			return $this->_db->fetchAll('SELECT `entity_id`,`entity_title`,`category_id`,`entity_pub_time` 
												FROM `tbl_entity` 
												WHERE `category_id` = '.$cat_id.' AND `entity_pub` = 1 ORDER BY `entity_pub_time` DESC');
		}
	}