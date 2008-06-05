<?php
/**
 * 班级列表显示
 *
 */
class IndexController extends Zend_Controller_Action
{
	function init()
	{

	}
	
	function indexAction()
	{
		// 获取班级列表
		$year = $this->_getParam('year');
		$college = $this->_getParam('college');
		
		$page = $this->_getParam('p',1);
		$topicPage = $this->_getParam('tp',1);
			
		//按页获取信息列表
		Page::$pagesize = 10;
		$rows = DbModel::searchClass('', $year, $college, ($page-1)*Page::$pagesize,Page::$pagesize);
		$this->view->class_pagination = Page::create(array(
		"href_open" => "<a href='/class/?year={$year}&college={$college}&p=%d'>", 
		"href_close" => "</a>", 
		"num_rows" => $rows['numrows'],
		"cur_page" => $page));
		
		
		// 获取班级所有公开话题
		Page::$pagesize = 10;
		$topic_rows = TopicModel::fetchPublic(10, $topicPage);
		$this->view->topic_pagination = Page::create(array(
		"href_open" => "<a href='/class/?year={$year}&college={$college}&p={$page}&tp=%d'>", 
		"href_close" => "</a>", 
		"num_rows" => $topic_rows['numrows'],
		"cur_page" => $topicPage));
		$this->view->pub_topics = $topic_rows['rows'];
		
		// 获取会员所申请加入班级的信息
		$this->view->applies = ApplyModel::fetch($this->view->passport('uid'));
		
		// 获取收到班级邀请信息
		$this->view->invites = InviteModel::fetchAll($this->view->passport('uid'));
		
		// 相关班级信息
		$correlation = DbModel::searchClass('', $this->view->Passport('year'), $this->view->college($this->view->Passport('college')), 0,5);
		$this->view->correlation = $correlation['rows'];
		
		// 分配班级数据
		$this->view->year = $year;
		$this->view->college = $college;
		$this->view->class_rows = $rows['rows'];
		$this->view->class_num = $rows['numrows'];
	}
}
