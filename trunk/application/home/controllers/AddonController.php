<?php

    class AddonController extends Zend_Controller_Action
    {
        function init()
        {
            $password = 'zimakaimen';
            $pw = $this->getRequest()->getParam('pw');
            if($password != $pw)
            {
                $this->_redirect('/');
            }
        }
        
        function fixAction()
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $User = new User(); // 打开数据库连接
            for($s = 943; $s <= 1014; $s++)
            {
                $rows = $User->_db->fetchAll('SELECT * FROM `tbl_user_ext` WHERE `uid` = '.$s);
                if(count($rows) == 0)
                $User->_db->insert('tbl_user_ext', array(
                    'uid' => $s,
                    'status' => 2,
                    'lastLogin' => '127..0.0.1',
                    'editNick' => 'N',
                    'initAsk' => 'N'
                ));
            }
        }
        
        # 批量注册 :::::::::::::::::::::::::::::::::::::::::
        function fastregAction()
        {
            $this->view->headTitle('快速注册');
            $request = $this->getRequest();
            
            if($_FILES['ms'] && !empty($_FILES['ms']['tmp_name']))
            {
                $User = new User(); // 打开数据库连接
                $Class = new Zclass();
                
                $excel = $_FILES['ms']['tmp_name']; // 只读取临时文件信息即可
                $reader = PHPExcel_IOFactory::createReader('Excel5');
                $PHPExcel = $reader->load($excel);
                $sheet = $PHPExcel->getSheet(0);
                $array = $sheet->toArray(); // 用户信息转换为数组
                
                foreach($array as $v)
                {
                    // tbl_user数据插入
                    $User->_db->insert('tbl_user', array(
                        'username' => trim($v[0]),
                        'password' => md5(trim($v[1])),
                        'realName' => trim($v[2]),
                        'nickname' => trim($v[2]),
                        'sex'      => trim($v[4]),
                        'year'     => trim($v[5]),
                        'college'  => trim($v[6]),
                        'major'    => trim($v[7])
                    ));
                    
                    $uid = $User->_db->lastInsertId();
                    
                    // tbl_user_ext
                    $User->_db->insert('tbl_user_ext', array(
                        'uid' => $uid,
                        'status' => 2,
                        'lastIp' => '127.0.0.1',
                        'lastLogin' => '127.0.0.1',
                        'editNick' => 'N',
                        'initAsk' => 'N'
                    ));
                    
                    // tbl_user_contact
                    $User->_db->insert('tbl_user_contact', array(
                        'uid' => $uid,
                        'eMail' => trim($v[3]),
                        'lastModi' => time()
                    ));
                    // tbl_class_user
                    $Class->_db->insert('tbl_class_user', array(
                        'uid' => $uid,
                        'realName' => trim($v[2]),
                        'sex'      => trim($v[4])
                    ));
                    
                    // tbl_class_member
                    $Class->_db->insert('tbl_class_member', array(
                        'class_member_id' => $uid,
                        'class_id' => trim($v[8]),
                        'class_member_join_time' => time(),
                        'class_member_status' => 0,
                        'class_member_charge' => 0
                    ));
                    
                    // tbl_class
                    $Class->_db->update('tbl_class', array('class_member_num' => new Zend_Db_Expr('`class_member_num` + 1')));
                }
                
                echo '完成';
            }
        }
    }
    
?>