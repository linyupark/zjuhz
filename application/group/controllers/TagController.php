<?php

/* 群组标签 */

class TagController extends Zend_Controller_Action
{
    function init()
    {}
    
    public function indexAction()
    {
        $query = urldecode($this->_getParam('q'));
        $sid = $this->_getParam('sid');
        $db = Zend_Registry::get('dbGroup');
        $select = $db->select()->from(array('g'=>'tbl_group'),
                                array('name','group_id','member_num','intro','tags'));
        Page::$pagesize = $pagesize = 10;
        $page = $this->_getParam('p', 1);
        $offset = ($page-1)*$pagesize;
        // 选出符合的群组
        if($sid != null) // 在同分类下进行查找
        {
            $select->where('sort_id = ?', $sid);
            $select->where('tags LIKE "%'.$query.'%"');
            $select->order('member_num DESC');
            
            $temp = $select->query()->fetchAll();
            $num_rows = count($temp);
            
            $select->limit($pagesize, $offset);
            
            $href_open = '<a href="/group/tag?sid='.$sid.'&q='.urlencode($query).'&p=%d">';
            
            $rows = $select->query()->fetchAll();
        }
        else
        {
            $select->where('tags LIKE "%'.$query.'%"');
            $select->order('member_num DESC');
            
            $temp = $select->query()->fetchAll();
            $num_rows = count($temp);
            
            $select->limit($pagesize, $offset);
            
            $href_open = '<a href="/group/tag?q='.urlencode($query).'&p=%d">';
            
            $rows = $select->query()->fetchAll();
        }
        Page::create(array(
            'href_open' => $href_open,
            'href_close' => '</a>',
            'num_rows' => $num_rows,
            'cur_page' => $page
        ));
        $this->view->headTitle($query);
        $this->view->groups = $rows;
        $this->view->pagination = Page::$page_str;
		$this->view->group_num = $num_rows;
        $this->view->sort_id = $sid;
        $this->view->query = $query;
    }
}

?>