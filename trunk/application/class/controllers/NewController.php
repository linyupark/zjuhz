<?php

class NewController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
	function indexAction()
	{
		$request = $this->getRequest();
		if($request->isPost())
		{
			$inputChains = new InputChains();
			$name = $inputChains->className($request->getPost('name'));
			$year = $inputChains->noEmpty($request->getPost('year'), '入学年份');
			$college = $inputChains->noEmpty($request->getPost('college'), '学院名称');
				
			if(count($inputChains->getMessages()) > 0) //数据有问题
			{
				$this->view->err_tip = $inputChains->getMessages();
			}
				
			else //插入数据库
			{
				$data = array(
					'class_name' => $name,
					'class_year' => $year,
					'class_college' => $college,
					'class_charge' => $this->view->login['uid'],
				);
				if(false == DbModel::initClass($data))
					$this->view->err_tip['class_name'] = '班级数据初始化失败';
				else 
				{
					// 将sessClass清除,重新分配
					$this->_sessClass->data = null;
					$this->view->suc_tip = '班级建立成功,2秒后转向<a href="/class/">班级主页</a>';
					echo Commons::js_jump('/class/',2);
				}
			}
		}
	}
}
