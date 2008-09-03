<?php

    class EventsController extends Zend_Controller_Action
    {
        
        function init()
        {
            $this->view->eid = $this->_getParam('eid');
        }
        
        # 活动列表集合
        function listAction()
        {
            $E = new EventsModel('dbEvent');
            
            $order = $this->_getParam('order', 'sign_close');
            $where = $this->_getParam('where');
            $page = $this->_getParam('p', 1);
            
            $select = $E->select()->order($order.' DESC');
            
            switch($where)
            {
                case 'join' : // 我参与的
                    $join_events = EventsModel::_dao()->fetchAll('SELECT `event_id`
                                                               FROM `tbl_events_member`
                                                               WHERE `member` = ?', Cmd::myid());
                    foreach($join_events as $e)
                    {
                        $select->orWhere('event_id = ?', $e['event_id']);
                    }
                break;
                
                case 'founder' : // 我组织的
                    $select->where('founder = ?', Cmd::myid());
                break;
                
                default :
                break;
            }
            
            $rows = $E->fetchAll($select);
            
            $num_rows = count($rows);
            
            Page::$pagesize = $pagesize = 20;
            
            Page::create(array(
                'href_open' => '<a href="/group/events/list?order='.$order.'&p=%d">',
                'href_close' => '</a>',
                'num_rows' => $num_rows,
                'cur_page' => $page
            ));
            
            $select->limit($pagesize, Page::$offset);
            
            $this->view->numrows = $num_rows;
            $this->view->rows = $E->fetchAll($select);
            $this->view->pagination = Page::$page_str;
            $this->view->page = $page;
            $this->view->order = $order;
            $this->view->where = $where;
        }
        
        # 保存参与者活动感言
        function reviewsaveAction()
        {
            $content = $this->_getParam('content');
            EventsModel::_dao()->update('tbl_events_member',
                                        array('review'=>nl2br(strip_tags($content))),
                                        'event_id = '.$this->_getParam('eid').' AND member = '.Cmd::myid());
            echo 'ok';
        }
        
        # 参与者活动感言
        function reviewAction()
        {
            $this->view = $this->getHelper('viewRenderer')->view;
            // 获取针对当前活动的感言
            $review = EventsModel::_dao()->fetchRow('SELECT *
                                                    FROM `tbl_events_member`
                                                    WHERE `event_id` = ? AND `member` = ?',
                                                    array($this->_getParam('eid'), Cmd::myid()));
            $this->view->review = $review;
            $this->render('review');
        }
        
        # 导出excel
        function xlsAction()
        {
            $this->getResponse()->insert('nav','');
            $this->getHelper('layout')->disableLayout();
            $this->getHelper('viewRenderer')->setNoRender();
            $E = new EventsModel('dbEvent');
            $where = $E->select()->where('event_id = ?', $this->view->eid);
            $event = $E->fetchRow($where);
            $members = EventsModel::joinMembers($this->view->eid);
            
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("zjuhz.com");
            $objPHPExcel->getProperties()->setLastModifiedBy("zjuhz.com");
            $objPHPExcel->getProperties()->setTitle($event->title.'活动人员名单');
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', '姓名');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', '学院');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', '入学年份');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', '手机');
            $row = 2;
            foreach($members as $m)
            {
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, UserModel::fetch($m['member'],'realName'));
                $objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, Cmd::getCollege(UserModel::fetch($m['member'],'college')));
                $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, UserModel::fetch($m['member'],'year'));
                $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, UserModel::fetch($m['member'],'ext_phone'));
                $row++;
            }
            $objPHPExcel->getActiveSheet()->setTitle($event->title.'活动人员名单');
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=hello.xlsx"); 
            header("Content-Transfer-Encoding: binary ");
            $objWriter->save('php://output');
        }
        
        # 参加活动的成员打印
        function membersAction()
        {
            $this->getResponse()->insert('nav','');
            $this->getHelper('layout')->disableLayout();
            $E = new EventsModel('dbEvent');
            $where = $E->select()->where('event_id = ?', $this->view->eid);
            $event = $E->fetchRow($where);
            $this->view->event = $event;
            $this->view->members = EventsModel::joinMembers($this->view->eid);
        }
        
        # 编辑活动
        function editAction()
        {
            $R = $this->getRequest();
            if($R->isPost()) // 修改了活动
            {
                $V = new Lp_Valid();
                
                $title = $V->of($R->getPost('title'), 'title', '活动名称', 'trim|required|str_between[6,200]');
                $sign_close = $V->of($R->getPost('sign_close'), 'sign_close', '报名结束时间', 'trim|required');
                $event_start = $V->of($R->getPost('event_start'), 'event_start', '活动开始时间', 'trim|required');
                $event_close = $V->of($R->getPost('event_close'), 'event_close', '活动结束时间', 'trim|required');
                $location = $R->getPost('province').$R->getPost('city').$V->of($R->getPost('location'), 'location', '活动地点', 'trim|required');
                
                if($V->getMessages() != false)
                {
                    echo '<div class="error">'.$V->getMessages('* ','<br />').'</div>';
                }
                
                else
                {
                    $data = array(
                        'title' => $title,
                        'sign_close' => strtotime($sign_close),
                        'event_start' => strtotime($event_start),
                        'event_close' => strtotime($event_close),
                        'location' => $location,
                        'intro' => $R->getPost('intro')
                    );
                    
                    EventsModel::_dao()->update('tbl_events', $data, 'event_id = '.$this->view->eid);
                    
                    echo '<div class="success">活动“'.$title.'”修改成功,页面即将跳转至<a href="/group/events/show?eid='.$this->view->eid.'">活动页</a></div>';
                    echo Commons::js_jump('/group/events/show?eid='.$this->view->eid, 1);
                }
            }
            
            
            $E = new EventsModel('dbEvent');
            $this->view->event = $E->fetchRow($E->select()->where('event_id = ?', $this->view->eid));
        }
        
        # 报名取消
        function getoutAction()
        {
            EventsModel::getOut($this->_getParam('eid'));
            echo 'success';
        }
        
        # 完善联系
        function extsaveAction()
        {
            $ext_phone = $this->_getParam('ext_phone');
            
            if(!is_numeric($ext_phone) || strlen($ext_phone)!=11)
            {
                echo '<div class="error">手机号码格式错误</div>';
            }
                
            else
            {
                UserModel::update(array('ext_phone' => $ext_phone), Cmd::myid());
                echo 'success';
            }
            
        }
        
        # 报名参加
        function joinAction()
        {
            // 检查其群组联系方式是否完整
            $phone = UserModel::fetch(Cmd::myid(), 'ext_phone');
            if($phone == null)
                $this->render('fillup');
                
            else
            {
                $this->getHelper('viewRenderer')->setNoRender();
                EventsModel::join($this->_getParam('eid'));
                echo 'success';
            }
        }
        
        # 活动页
        function showAction()
        {
            $E = new EventsModel('dbEvent');
            $where = $E->select()->where('event_id = ?', $this->view->eid);
            $event = $E->fetchRow($where);
            $this->view->event = $event;
            
            // 保存活动感言
            $R = $this->getRequest();
            if($R->isPost())
            {
                $role = $R->getPost('role');
                $content = $R->getPost('content');

                $event->review = $content;
                $event->save();
            }
            
            // 活动日期显示
            if(date('y-m-d', $event->event_start) == date('y-m-d', $event->event_close))
            $format = 'H:i'; else $format = 'y年m月d日 H:i';
            $this->view->event_life = date('y年m月d日 H:i', $event->event_start).'至'.date($format, $event->event_close);
            
            // 报名/取消报名
            $members = EventsModel::joinMembers($this->view->eid);

            $isJoin = array(
                'value' => '报名参加',
                'func' => 'join()'
            );
            
            $reviews = array();
            
            foreach($members as $m)
            {
                if($m['member'] == Cmd::myid())
                $isJoin = array(
                    'value' => '取消报名',
                    'func' => 'getOut()'
                );
                if($m['review'] != null)
                $reviews[$m['member']] = $m['review'];
            }
            
            $this->view->reviews = $reviews;
            $this->view->members = $members;
            $this->view->isJoin = $isJoin;
        }
        
        # 发起活动
        function createAction()
        {
            $R = $this->getRequest();
            if($R->isPost()) // 提交了新活动
            {
                $V = new Lp_Valid();
                
                $title = $V->of($R->getPost('title'), 'title', '活动名称', 'trim|required|str_between[6,200]');
                $sign_close = $V->of($R->getPost('sign_close'), 'sign_close', '报名结束时间', 'trim|required');
                $event_start = $V->of($R->getPost('event_start'), 'event_start', '活动开始时间', 'trim|required');
                $event_close = $V->of($R->getPost('event_close'), 'event_close', '活动结束时间', 'trim|required');
                $location = $R->getPost('province').$R->getPost('city').$V->of($R->getPost('location'), 'location', '活动地点', 'trim|required');
                
                if($V->getMessages() != false)
                {
                    echo '<div class="error">'.$V->getMessages('* ','<br />').'</div>';
                }
                
                else
                {
                    $data = array(
                        'group_id' => 0,
                        'title' => $title,
                        'founder' => Cmd::myid(),
                        'sign_close' => strtotime($sign_close),
                        'event_start' => strtotime($event_start),
                        'event_close' => strtotime($event_close),
                        'location' => $location,
                        'intro' => $R->getPost('intro')
                    );
                    
                    $E = new EventsModel('dbEvent');
                    $event = $E->createRow($data);
                    $event->save();
                    
                    $event_id = $E->_dao()->lastInsertId();
                    
                    echo '<div class="success">活动“'.$title.'”发布成功,页面即将跳转至<a href="/group/events/show?eid='.$event_id.'">活动页</a></div>';
                    echo Commons::js_jump('/group/events/show?eid='.$event_id, 1);
                }
            }
            
            $this->view->gid = 0;
        }
    }