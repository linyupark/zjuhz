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
		 * 资讯总数
		 *
		 * @return string
		 */
		function howmany()
		{
			$row = $this->_db->fetchRow('SELECT
				COUNT(`entity_id`) AS `numrows` FROM `tbl_entity`
			');
			return $row['numrows'];
		}
		
		/**
		 * 返回最新的资讯
		 *
		 * @param integer $limit
		 * @return array
		 */
		function get_new($limit = 5)
		{
			return $this->_db->fetchAll('SELECT
				`entity_id`,`entity_title`,`category_id`,`entity_pub_time`,`category_icon`,`category_name`,`entity_top` 
				FROM `vi_entity`
				WHERE `entity_pub` = 1 AND `category_id` != 12 AND `category_id` != 6 ORDER BY `entity_top` DESC,`entity_pub_time` DESC LIMIT '.$limit);
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
												WHERE `category_id` = '.$cat_id.' AND `entity_pub` = 1 ORDER BY `entity_pub_time` DESC LIMIT 5');
		}
	}