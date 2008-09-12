<?php

    /**
     * 密码找回控制
     */
    class PassportController extends Zend_Controller_Action
    {
		function initAction()
		{
			$account = Commons::decrypt($this->_getParam('code'));
			$new_pw = $this->_getParam('pw');
			
			$User = new User();
			$User->_db->update('tbl_user', array('password' => md5($new_pw)), 'username = "'.$account.'"');
			
			$this->view->new_pw = $new_pw;
		}
		
        function findAction()
        {
            $R = $this->getRequest();
            if($R->isPost())
            {
                $V = new Lp_Valid();
                $account = strip_tags(trim($R->getPost('account')));
                $email = $V->of($R->getPost('email'), 'email', '邮箱', 'valid_email');
                if($V->getMessages() != false)
                echo $V->getMessages('* ');
                else
                {
                    $User = new User();
                    if($User->check($account, $email) == false)
                    {
                        echo '* 帐号和邮箱不符';
                    }
                    else // 发送邮件
                    {
                        $code = Commons::encrypy($account);
                        $new_password = Commons::getRandomStr(time(), 6);
                        
                        $config = Zend_Registry::get('iniConfig')->mail->gmail->toArray(); // 载入ini的配置

		                $mail   = new Zend_Mail('utf-8');
                        
                        $mail->setFrom($config['username'], 'zjuhz.com 密码找回') // 配置中的发信方
                            ->addTo($email, $account) // 数据表中的收信方
                            ->setSubject('密码找回系统信') // 配置中的邮件主题
                            ->setBodyText('请点击链接,即可初始化密码
										  http://www.zjuhz.com/home/passport/init?code='.$code.'&pw='.$new_password) // 表单中的邮件正文
                            ->send(new Zend_Mail_Transport_Smtp($config['name'], $config));
						echo 'success';
                    }
                }
            }
        }
    }

?>