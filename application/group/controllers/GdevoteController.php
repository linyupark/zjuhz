<?php

    /**
     * 热心度 */
    
    class GdevoteController extends Zend_Controller_Action
    {
        function init()
        {
            
        }
        
        # 获取用户数据对象
        function userDb()
        {
            $iniDb = new Zend_Config_Ini('Ini/Db.ini');
            $params = $iniDb->default->params->toArray();
            $params['dbname'] = 'zjuhz_user';
            $db = Zend_Db::factory($iniDb->default->adapter, $params);
            $db->query('SET NAMES "utf8"');
            return $db;
        }
        
        # 获取群组数据对象
        function groupDb()
        {
        	$iniDb = new Zend_Config_Ini('Ini/Db.ini');
            $params = $iniDb->default->params->toArray();
            $params['dbname'] = 'zjuhz_group';
            $db = Zend_Db::factory($iniDb->default->adapter, $params);
            return $db;
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
        
        # json获取热心度
        function levelAction()
        {
        	$gid = $this->_getParam('gid');
        	$row = DevoteModel::_dao()->fetchRow('SELECT `point` FROM `tbl_group` WHERE `gid` = ?', $gid);
        	$point = $row['point'];
        	
        	Alp_Sys::msg('point', $point);
        	echo Alp_Sys::getMsgJson();
        }
        
        # 修改记录
        function grademodAction()
        {
        	$this->isHandler();
        	
        	$params = $this->getRequest()->getParams();
        	$Devote = new DevoteModel('dbDevote');
        	$row = $Devote->fetchRow($Devote->select()->where('id = ?', $params['id']));
        	if($this->getRequest()->isPost())
        	{
        		$point = $row->point; // 原分
        		$point_change = $this->_getParam('point') - $point;
        		$row->point = $this->_getParam('point');
        		$row->reason = $this->_getParam('reason');
        		$row->time = strtotime($this->_getParam('time'));
        		$row->handler = $this->_getParam('handler');
        		$row->save();
        		// 重新计算总分
        		DevoteModel::_dao()->update('tbl_group', array(
	                'point' => new Zend_Db_Expr('point + '.$point_change)
	            ), 'gid = '.$row->gid);
        		echo 'done';
        	}
        	else 
        	{
        		$this->view = $this->getHelper('viewRenderer')->view;
	        	$this->view->r = $row;
	        	$this->view->id = $params['id'];
	        	$this->view->gid = $params['gid'];
	        	$this->view->page = $params['p'];
	        	$this->render('grademod');
        	}
        }
        
        # 删除加分记录
        function gradedelAction()
        {
        	$this->isHandler();
        	
            $Devote = new DevoteModel('dbDevote');
            $id = $this->_getParam('id');
            $row = $Devote->fetchRow($Devote->select()->where('id = ?', $id));
            $gid = $row->gid;
            $point = $row->point;
            $row->delete();
            
            DevoteModel::_dao()->update('tbl_user', array(
                'point' => new Zend_Db_Expr('point - '.$point)
            ), 'gid = '.$gid);
            
            echo 'success';
        }
        
        # 保存加分记录
        function gradesaveAction()
        {
        	$this->isHandler();
        	
            $params = $this->getRequest()->getPost();
            $V = new Lp_Valid();
            $time = $V->of($params['time'], 'time', '记录时间', 'trim|timeformat');
            $point = $V->of($params['point'], 'point', '分数', 'trim|required');
            $reason = $V->of($params['reason'], 'reason', '加分说明', 'trim|strip_tags|required');
            if($V->getMessages() != false)
            {
                echo '<ul class="error">'.$V->getMessages('<li>','</li>').'</ul>';
            }
            else // 加入数据
            {
                $Devote = new DevoteModel('dbDevote');
                $row = $Devote->createRow(array(
                    'gid' => $params['gid'],
                    'time' => strtotime($time),
                    'point' => $point,
                    'handler' => $params['handler'],
                    'reason' => nl2br($reason),
                	'uid' => 0,
                ));
                $row->save();
                DevoteModel::_dao()->update('tbl_group', array('point'=>new Zend_Db_Expr('point+'.$params['point'])), 'gid = '.$params['gid']);
                echo 'success';
            }
        }
        
        # 加分详细信息列
        function gradeAction()
        {
        	$this->isHandler();
        	
            $gid = $this->_getParam('gid');
            $page = $this->_getParam('p', 1);
            $row = DevoteModel::_dao()->fetchRow('SELECT `gid`,`name` FROM `tbl_group` WHERE `gid` =?',$gid);
            
            $Devote = new DevoteModel('dbDevote');
            $select = $Devote->select()->where('gid = ?', $row['gid']);
            $transactions = $Devote->fetchAll($select);
            
            $num_rows = count($transactions);
            
            $pagesize = Page::$pagesize = 5;
            $pagination = Page::create(array(
                'href_open' => '<a href="javascript:grade('.$gid.',%d)">',
                'href_close' => '</a>',
                'num_rows' => $num_rows,
                'cur_page' => $page
            ));
            
            $select->order('id DESC')->limit($pagesize, Page::$offset);
            $transactions = $Devote->fetchAll($select);
            
            $this->view = $this->getHelper('viewRenderer')->view;
            $this->view->trans = $transactions;
            $this->view->page = $page;
            $this->view->gid = $row['gid'];
            $this->view->pagination = $pagination;
            $this->view->name = $row['name'];
            $this->render('grade');
        }
        
        # 导入群组数据
        function importAction()
        {
        	$this->isHandler();
        	
        	// 获取全部群组信息
        	$groups = $this->groupDb()->fetchAll('SELECT `group_id`,`name` FROM `tbl_group`');
        	foreach($groups as $v)
        	{
        		if(!DevoteModel::_dao()->fetchRow('SELECT `gid` FROM `tbl_group` WHERE `gid` = '.$v['group_id']))
        		DevoteModel::_dao()->insert('tbl_group', array('gid'=>$v['group_id'],'name'=>$v['name']));
        	}
        	echo '导入完成，请刷新页面';
        }
        
        # 创建热心度记录
        function createAction()
        {
            
        }
        
        # 热心度操作首页
        function indexAction()
        {
        	$this->isHandler();
        	
            $db = DevoteModel::_dao();
            $name = $this->_getParam('s');
            $gid = $this->_getParam('gid');
            $order = $this->_getParam('order', 'gid');
            $t = $this->_getParam('t', 'desc');
            $this->view->name = $name;
            $this->view->gid = $gid;
            $this->view->t = $t;
            if($gid != null)
            {
            	$rows = $db->fetchAll('SELECT * FROM `tbl_group` WHERE `gid` = '.(int)$gid);
            	$this->view->rows = $rows;
            }
            else 
            {
	            // 罗列所有热心度群组
	            if($name == null)
	            $row = $db->fetchRow('SELECT COUNT(`gid`) AS `numrows` FROM `tbl_group`');
	            else
	            $row = $db->fetchRow('SELECT COUNT(`gid`) AS `numrows` FROM `tbl_group`
	                                 WHERE `name` LIKE "%'.urldecode($name).'%"');
	            
	            $numrows = $row['numrows'];
	            $rows = null;
	            
	            if($numrows > 0) // 有记录
	            {
	                $page = $this->_getParam('p', 1);
	                $pagesize = Page::$pagesize = 10;
	                
	                Page::create(array(
	                    'href_open' => '<a href="/group/gdevote?s='.$name.'&order='.$order.'&t='.$t.'&p=%d">',
	                    'href_close' => '</a>',
	                    'num_rows' => $numrows,
	                    'cur_page' => $page
	                ));
	                
	                $offset = Page::$offset;
	                
	                if($name == null)
	                $rows = $db->fetchAll('SELECT * FROM `tbl_group` ORDER BY `'.$order.'` '.$t.'
	                                      LIMIT '.$offset.','.$pagesize);
	                else
	                $rows = $db->fetchAll('SELECT * FROM `tbl_group` WHERE `name` LIKE "%'.urldecode($name).'%" 
	                                      ORDER BY `'.$order.'` '.$t.' 
	                                      LIMIT '.$offset.','.$pagesize);
	            }
	            $this->view->rows = $rows;
            	$this->view->pagination = Page::$page_str;
            }
        }
        
        # 显示阻止信息
        function denyAction()
        {
            
        }
    }