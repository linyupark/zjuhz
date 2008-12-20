<?php

    /**
     * 热心度 */
    
    class DevoteController extends Zend_Controller_Action
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
        
        # 是否为可操作热心度的成员
        function isHandler()
        {
            $handler = array(6,4,5,493,488); // 允许的id数组
            
            if(!in_array(Cmd::myid(), $handler))
            {
                $this->_forward('deny');
            }
        }
        
        function infoAction()
        {
        	$select = $this->userDb()->select();
        	$select->from(array('base' => 'tbl_user'))
        	       ->joinLeft(array('contact' => 'tbl_user_contact'), 'base.uid = contact.uid');
        	$rows = $select->query()->fetchAll();
        	
        	if($this->_getParam('type') == 'xls')
        	{
        		$this->getResponse()->insert('nav','');
	            $this->getHelper('layout')->disableLayout();
	            $this->getHelper('viewRenderer')->setNoRender();
        		$objPHPExcel = new PHPExcel();
	            $objPHPExcel->getProperties()->setCreator("zjuhz.com");
	            $objPHPExcel->getProperties()->setLastModifiedBy("zjuhz.com");
	            $objPHPExcel->getProperties()->setTitle('注册会员名单');
	            $objPHPExcel->setActiveSheetIndex(0);
	            $objPHPExcel->getActiveSheet()->SetCellValue('A1', '姓名');
	            $objPHPExcel->getActiveSheet()->SetCellValue('B1', '性别');
	            $objPHPExcel->getActiveSheet()->SetCellValue('C1', '教育');
	            $objPHPExcel->getActiveSheet()->SetCellValue('D1', '家乡');
	            $objPHPExcel->getActiveSheet()->SetCellValue('E1', '现住');
	            $objPHPExcel->getActiveSheet()->SetCellValue('F1', '电子邮件');
	            $objPHPExcel->getActiveSheet()->SetCellValue('G1', '手机号');
	            $row = 2;
	            foreach($rows as $m)
	            {
	                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $m['realName']);
	                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $m['sex']);
	                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $m['major'].'('.$m['year'].')');
	                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $m['hometown_p'].$m['hometown_c'].$m['hometown_a']);
	                $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $m['location_p'].$m['location_c'].$m['location_a']);
	                $objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $m['eMail']);
	                $objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $m['mobile']);
	                $row++;
	            }
	            $objPHPExcel->getActiveSheet()->setTitle('注册会员名单');
	            $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	            header("Pragma: public");
	            header("Expires: 0");
	            header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
	            header("Content-Type: application/force-download");
	            header("Content-Type: application/octet-stream");
	            header("Content-Type: application/download");
	            header("Content-Disposition: attachment;filename=member_contact.xls"); 
	            header("Content-Transfer-Encoding: binary ");
	            $objWriter->save('php://output');
        	}
        	else $this->view->rows = $rows;
        }
        
        # json获取热心度
        function levelAction()
        {
        	$uid = $this->_getParam('uid');
        	$row = DevoteModel::_dao()->fetchRow('SELECT `point` FROM `tbl_user` WHERE `uid` = ?', $uid);
        	$point = $row['point'];
        	$level = Zend_Registry::get('iniConfig')->level->p->toArray();
        	
        	$result = array('p'=>0, 'level'=>'');
        	foreach($level as $p => $v)
        	{
        		if($point >= $p && $result['p'] <= $p)
        		{
        			$result['p'] = $p;
        			$result['level'] = $v;
        		}
        	}
        	Alp_Sys::msg('point', $point);
        	Alp_Sys::msg('level', $result['level']);
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
        		DevoteModel::_dao()->update('tbl_user', array(
	                'point' => new Zend_Db_Expr('point + '.$point_change)
	            ), 'uid = '.$row->uid);
        		echo 'done';
        	}
        	else 
        	{
        		$this->view = $this->getHelper('viewRenderer')->view;
	        	$this->view->r = $row;
	        	$this->view->id = $params['id'];
	        	$this->view->uid = $params['uid'];
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
            $uid = $row->uid;
            $point = $row->point;
            $row->delete();
            
            DevoteModel::_dao()->update('tbl_user', array(
                'point' => new Zend_Db_Expr('point - '.$point)
            ), 'uid = '.$uid);
            
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
                    'uid' => $params['uid'],
                    'time' => strtotime($time),
                    'point' => $point,
                    'handler' => $params['handler'],
                    'reason' => nl2br($reason)
                ));
                $row->save();
                DevoteModel::_dao()->update('tbl_user', array('point'=>new Zend_Db_Expr('point+'.$params['point'])), 'uid = '.$params['uid']);
                echo 'success';
            }
        }
        
        # 加分详细信息列
        function gradeAction()
        {
        	$this->isHandler();
        	
            $uid = $this->_getParam('uid');
            $page = $this->_getParam('p', 1);
            $row = DevoteModel::_dao()->fetchRow('SELECT `uid`,`name` FROM `tbl_user` WHERE `uid` =?',$uid);
            
            $Devote = new DevoteModel('dbDevote');
            $select = $Devote->select()->where('uid = ?', $row['uid']);
            $transactions = $Devote->fetchAll($select);
            
            $num_rows = count($transactions);
            
            $pagesize = Page::$pagesize = 5;
            $pagination = Page::create(array(
                'href_open' => '<a href="javascript:grade('.$uid.',%d)">',
                'href_close' => '</a>',
                'num_rows' => $num_rows,
                'cur_page' => $page
            ));
            
            $select->order('id DESC')->limit($pagesize, Page::$offset);
            $transactions = $Devote->fetchAll($select);
            
            $this->view = $this->getHelper('viewRenderer')->view;
            $this->view->trans = $transactions;
            $this->view->page = $page;
            $this->view->uid = $row['uid'];
            $this->view->pagination = $pagination;
            $this->view->name = $row['name'];
            $this->render('grade');
        }
        
        # 导入用户数据
        function importAction()
        {
        	$this->isHandler();
        	
        	// 获取全部用户信息
        	$users = $this->userDb()->fetchAll('SELECT `uid`,`realName` FROM `tbl_user`');
        	foreach($users as $v)
        	{
        		if(!DevoteModel::_dao()->fetchRow('SELECT `uid` FROM `tbl_user` WHERE `uid` = '.$v['uid']))
        		DevoteModel::_dao()->insert('tbl_user', array('uid'=>$v['uid'],'name'=>$v['realName']));
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
            $uid = $this->_getParam('uid');
            $order = $this->_getParam('order', 'uid');
            $t = $this->_getParam('t', 'desc');
            $this->view->name = $name;
            $this->view->uid = $uid;
            $this->view->t = $t;
            if($uid != null)
            {
            	$rows = $db->fetchAll('SELECT * FROM `tbl_user` WHERE `uid` = '.(int)$uid);
            	$this->view->rows = $rows;
            }
            else 
            {
	            // 罗列所有热心度成员
	            if($name == null)
	            $row = $db->fetchRow('SELECT COUNT(`uid`) AS `numrows` FROM `tbl_user`');
	            else
	            $row = $db->fetchRow('SELECT COUNT(`uid`) AS `numrows` FROM `tbl_user`
	                                 WHERE `name` LIKE "%'.urldecode($name).'%"');
	            
	            $numrows = $row['numrows'];
	            $rows = null;
	            
	            if($numrows > 0) // 有记录
	            {
	                $page = $this->_getParam('p', 1);
	                $pagesize = Page::$pagesize = 10;
	                
	                Page::create(array(
	                    'href_open' => '<a href="/group/devote?s='.$name.'&order='.$order.'&t='.$t.'&p=%d">',
	                    'href_close' => '</a>',
	                    'num_rows' => $numrows,
	                    'cur_page' => $page
	                ));
	                
	                $offset = Page::$offset;
	                
	                if($name == null)
	                $rows = $db->fetchAll('SELECT * FROM `tbl_user` ORDER BY `'.$order.'` '.$t.'
	                                      LIMIT '.$offset.','.$pagesize);
	                else
	                $rows = $db->fetchAll('SELECT * FROM `tbl_user` WHERE `name` LIKE "%'.urldecode($name).'%" 
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