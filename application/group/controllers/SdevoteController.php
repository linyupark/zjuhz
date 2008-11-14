<?php

    /**
     * 热心度(处理群组提交的加分申请) */
    
    class SdevoteController extends Zend_Controller_Action
    {
    	private $params;
    	
    	function init()
    	{
    		$this->params = $this->getRequest()->getParams();
    	}
    	
    	# 是否为可操作热心度的成员
        function isHandler()
        {
            $handler = array(6,4,5,493,488); // 允许的id数组
            
            if(!in_array(Cmd::myid(), $handler))
            {
                $this->_forward('deny');
            }
        }
    	
    	/**
    	 * 待审核的申请
    	 *
    	 */
    	function indexAction()
    	{
    		$this->isHandler();
    		
    		$Devote = DevoteModel::_dao();
    		$select = $Devote->select()->from('vi_apply')->where('ispass = ?', 0);
    		$rows = $select->query()->fetchAll();
    		$num_rows = count($rows);
    		$pagesize = Alp_Page::$pagesize = 20;
    		if($num_rows > $pagesize)
    		{
    			Alp_Page::create(array(
    				'href_open' => '<a href="/group/Sdevote?p=%d">',
    				'href_close' => '</a>',
    				'num_rows' => $num_rows,
    				'cur_page' => $this->_getParam('p', 1)
    			));
    			$select->limit($pagesize, Alp_Page::$offset);
    		}
    		$this->view->applies = $select->query()->fetchAll();
    		$this->view->pagination = Alp_Page::$page_str;
    	}
    	
    	function dopassAction()
    	{
    		$this->isHandler();
    		
    		$value = (int)$this->params['v'];
    		
    		if(count($this->params['id']) == 0)
    		{
    			echo '请钩选需要处理的申请';
    			return false;
    		}
    		
    		$Devote = DevoteModel::_dao();
    		
    		if($value == 1) // 通过
    		{
    			foreach($this->params['id'] as $id)
    			{
    				$row = $Devote->fetchRow('SELECT `uid`,`handler`,`time`,`reason`,`point` 
    									FROM `tbl_apply` WHERE `id` = ?', $id);
    				if($row!=false)
    				{
    					$Devote->insert('tbl_transaction', array(
    						'uid' => $row['uid'],
    						'handler' => $row['handler'],
    						'time' => $row['time'],
    						'reason' => $row['reason'],
    						'point' => $row['point']
    					));
    					// 加其总分
    					$Devote->update('tbl_user', array('point'=> new Zend_Db_Expr('point+'.$row['point'])), 'uid='.$row['uid']);
    					// 改变审核状态
    					$Devote->update('tbl_apply', array('ispass'=>1), 'id='.$id);
    				}
    			}
    		}
    		
    		if($value == 2)
    		{
    			foreach($this->params['id'] as $id)
    			{
    				// 改变审核状态
    				$Devote->update('tbl_apply', array('ispass'=>2), 'id='.$id);
    			}
    		}
    		echo 'success';
    	}
    	
    	# 显示阻止信息
        function denyAction()
        {
            
        }
    }