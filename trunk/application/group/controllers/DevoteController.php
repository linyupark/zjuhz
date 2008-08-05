<?php

    /**
     * 热心度 */
    
    class DevoteController extends Zend_Controller_Action
    {
        function init()
        {
            $this->isHandler();
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
            $handler = array(6,4); // 允许的id数组
            
            if(!in_array(Cmd::myid(), $handler))
            {
                $this->_forward('deny');
            }
        }
        
        # 删除加分记录
        function gradedelAction()
        {
            $Devote = new DevoteModel('dbDevote');
            $tran = $this->_getParam('tran');
            $row = $Devote->fetchRow($Devote->select()->where('transaction_id = ?', $tran));
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
                    'time' => strtotime($params['time']),
                    'point' => $params['point'],
                    'handler' => $params['handler'],
                    'reason' => nl2br($params['reason'])
                ));
                $row->save();
                DevoteModel::_dao()->update('tbl_user', array('point'=>new Zend_Db_Expr('point+'.$params['point'])), 'uid = '.$params['uid']);
                echo 'success';
            }
        }
        
        # 加分详细信息列
        function gradeAction()
        {
            $uid = $this->_getParam('uid');
            $page = $this->_getParam('p', 1);
            $row = DevoteModel::_dao()->fetchRow('SELECT `name` FROM `tbl_user` WHERE `uid` =?',$uid);
            
            $Devote = new DevoteModel('dbDevote');
            $select = $Devote->select()->where('uid = ?', $uid);
            $transactions = $Devote->fetchAll($select);
            
            $num_rows = count($transactions);
            
            $pagesize = Page::$pagesize = 10;
            $pagination = Page::create(array(
                'href_open' => '<a href="javascript:grade('.$uid.',%d)">',
                'href_close' => '</a>',
                'num_rows' => $num_rows,
                'cur_page' => $page
            ));
            
            $select->order('transaction_id DESC')->limit($pagesize, Page::$offset);
            $transactions = $Devote->fetchAll($select);
            
            $this->view = $this->getHelper('viewRenderer')->view;
            $this->view->trans = $transactions;
            $this->view->page = $page;
            $this->view->uid = $uid;
            $this->view->pagination = $pagination;
            $this->view->name = $row['name'];
            $this->render('grade');
        }
        
        # 修改用户基础数据
        function modAction()
        {
            $uid = $this->_getParam('uid');
            $member_id = $this->_getParam('member_id');
            $name = $this->_getParam('name');
            
            $db = DevoteModel::_dao();
            $db->update('tbl_user', array(
                'member_id' => $member_id,
                'name' => $name
            ), 'uid = '.$uid);
            echo 'success';
        }
        
        # 创建热心度记录
        function createAction()
        {
            $R = $this->getRequest();
            if($R->isPost())
            {
                $realy = $this->_getParam('realy');
                $name = trim($R->getPost('name'));
                $db = DevoteModel::_dao();
                
                if(empty($name))
                {
                    echo '姓名不能为空';
                    return false;
                }
                
                // 检查表单中是否有相同姓名的记录
                $isExist = $db->fetchRow('SELECT `uid` FROM `tbl_user` 
                                         WHERE `name` = ?', $name);
                
                if($isExist != false && $realy == 'no') // 无法确定是否需要再创建同名信息
                echo 'realy';
                else // 创建新信息
                {
                    // 检查网站注册用户中有无相关用户
                    $userDb = $this->userDb();
                    $row = $userDb->fetchRow('SELECT `uid` FROM `tbl_user`
                                             WHERE `realName` = ?', $name);
                    
                    $member_id = ($row != false) ? $row['uid'] : 0;
                    $db->insert('tbl_user',array(
                        'member_id' => $member_id,
                        'name' => $name,
                        'point' => 0
                    ));
                    echo 'success';
                }
            }
        }
        
        # 热心度操作首页
        function indexAction()
        {
            $db = DevoteModel::_dao();
            $name = $this->_getParam('s');
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
                    'href_open' => '<a href="/group/devote?p=%d">',
                    'href_close' => '</a>',
                    'num_rows' => $numrows,
                    'cur_page' => $page
                ));
                
                $offset = Page::$offset;
                
                
                
                if($name == null)
                $rows = $db->fetchAll('SELECT * FROM `tbl_user` ORDER BY `uid` DESC
                                      LIMIT '.$offset.','.$pagesize);
                else
                $rows = $db->fetchAll('SELECT * FROM `tbl_user` WHERE `name` LIKE "%'.urldecode($name).'%" 
                                      ORDER BY `uid` DESC 
                                      LIMIT '.$offset.','.$pagesize);
            }
            
            $this->view->rows = $rows;
            $this->view->pagination = Page::$page_str;
        }
        
        # 显示阻止信息
        function denyAction()
        {
            
        }
    }