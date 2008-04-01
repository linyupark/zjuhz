<?php 
	
	/**
	 * 资讯信息对外的接口控制器
	 *
	 */
	class Info_Xml_Rpc
	{
		protected $_db;
		
		function __construct()
		{
			$this->_db = Zend_Registry::get('db_info');
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
		 * @param string $cat_id
		 * @return array
		 */
		function get_arts_snapshot($cat_id)
		{
			return $this->_db->fetchAll('SELECT `entity_id`,`entity_title`,`category_id`,`entity_pub_time` 
												FROM `tbl_entity` 
												WHERE `category_id` = '.$cat_id.' ORDER BY `entity_pub_time` DESC');
		}
	}

	class ApiController extends Zend_Controller_Action 
	{
		function init()
		{
			//没有VIEW渲染
			$this->_helper->ViewRenderer->setNoRender(true);
			//没有布局
			$this->_helper->layout->disableLayout();
		}
		
		function indexAction()
		{
			$server = new Zend_XmlRpc_Server();
			$server->setClass('Info_Xml_Rpc','info');
			echo $server->handle();
		}
	}