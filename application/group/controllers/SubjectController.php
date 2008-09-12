<?php

    class SubjectController extends Zend_Controller_Action
    {
        function init()
        {
            $this->getResponse()->insert('nav','');
            $this->_helper->layout->disableLayout();
        }
        
        # 成立大会
        function foundAction()
        {
        	$total_num = 300;
            $root = $_SERVER['DOCUMENT_ROOT'];
            $myid = Cmd::myid();
            
            $db = file_get_contents($root.'/static/foundb.txt');
            
            if($db != '') // 空的数据
            {
                $list = unserialize($db);
                if(@array_key_exists($myid, $list))
                $this->view->signup = true;
                $this->view->tnum = $list[$myid]['tnum'];
            }
            else {
            	$list = null; // 默认名单为空
            }
            
         	// 计算出还可以索要多少票
	        $tcount = 0;
	        if($list != FALSE)
	        {
	        	foreach($list as $uid => $x)
		        {
                    if($uid == $myid) // 到了当前用户登记的位置
                    $my_t_start = $tcount+1; //第X
                    $my_t_end = ($my_t_start+$x['tnum']-1); //到y
                    
		            $tcount += $x['tnum'];
		        }
		        $this->view->last = $total_num - $tcount;
                $this->view->t_start = $my_t_start;
                $this->view->t_end = $my_t_end;
	        }
	        else $this->view->last = $total_num;
            
            $R = $this->getRequest();
            
            // 查看已经报名人员
            if($this->_getParam('list') == 'view')
            {
                $this->view->listview = $list;
                
                if($this->_getParam('export') == 'xls') // 导出xls
                {
                    $this->getHelper('viewRenderer')->setNoRender();
                    $objPHPExcel = new PHPExcel();
                    $objPHPExcel->getProperties()->setCreator("zjuhz.com");
                    $objPHPExcel->getProperties()->setLastModifiedBy("zjuhz.com");
                    $objPHPExcel->getProperties()->setTitle("杭州浙江大学校友会成立大会网上索票人员名单");
                    $objPHPExcel->getProperties()->setSubject("索票人员名单");
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objPHPExcel->getActiveSheet()->SetCellValue('A1', '姓名');
                    $objPHPExcel->getActiveSheet()->SetCellValue('B1', '邮箱');
                    $objPHPExcel->getActiveSheet()->SetCellValue('C1', '手机');
                    $objPHPExcel->getActiveSheet()->SetCellValue('D1', '入学年份');
                    $objPHPExcel->getActiveSheet()->SetCellValue('E1', '专业');
                    $objPHPExcel->getActiveSheet()->SetCellValue('F1', '地址');
                    $objPHPExcel->getActiveSheet()->SetCellValue('G1', '票数');
                    $row = 2;
                    foreach($list as $data)
                    {
                        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $data['name']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $data['email']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $data['mobile']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $data['year']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $data['major']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $data['address']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$row, $data['tnum']);
                        $row++;
                    }
                    $objPHPExcel->getActiveSheet()->setTitle('杭州浙江大学校友会成立大会网上索票人员名单');
                    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    header("Content-Disposition: attachment;filename=found.xls"); 
                    header("Content-Transfer-Encoding: binary ");
                    $objWriter->save('php://output');
                }
            }
            
            // 报名提交
            if($R->isPost())
            {
                $V = new Lp_Valid();
                $name = $V->of($R->getPost('name'), 'name', '姓名', 'trim|strip_tags|required');
                $email = $V->of($R->getPost('email'), 'email', '邮箱', 'trim|valid_email');
                $mobile = $V->of($R->getPost('mobile'), 'mobile', '手机', 'trim|numeric');
                $address = $V->of($R->getPost('address'), 'address', '邮寄地址', 'trim|strip_tags|required');
                $major = $V->of($R->getPost('major'), 'major', '专业', 'trim|strip_tags|required');
                $year = $V->of($R->getPost('year'), 'year', '入学年份', 'trim|numeric');
                $tnum = (int)$R->getPost('tnum');
                $uid = (int)$R->getPost('uid');
                
                if($V->getMessages() != false)
                {
                    echo '<br /><div class="error">'.$V->getMessages('* ').'</div>';
                }
                else
                {
                    $db = file_get_contents($root.'/static/foundb.txt');
                    if($db == '') // 空的数据
                    {
                        $list = array();
                        $list[$uid] = array(
                            'name' => $name,
                            'email' => $email,
                            'mobile' => $mobile,
                        	'year' => $year,
                        	'major' => $major,
                        	'address' => $address,
                        	'tnum' => $tnum
                        );
                        file_put_contents($root.'/static/foundb.txt', serialize($list));
                        echo 'success';
                    }
                    else // 补充数据
                    {
                        $list = unserialize($db);

                         // 写入数据
                         $list[$uid] = array(
                             'name' => $name,
                             'email' => $email,
                             'mobile' => $mobile,
                           	 'year' => $year,
	                         'major' => $major,
	                         'address' => $address,
	                         'tnum' => $tnum
                          );
                          // 保存进txt
                          file_put_contents($root.'/static/foundb.txt', serialize($list));
                          echo 'success';
                    }
                }
            }
            
            $this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
            $this->view->pswd = Commons::decrypt($_COOKIE['zjuhz_member']['pswd']); // 记住账号
        }
        
        #2008毕业校友
        function zju2008Action()
        {
            $this->view->cache = CacheModel::init(null, 600);
            $this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
        }
    }