<?php

class NewController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessClass = Zend_Registry::get('sessClass');
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
			
			$this->view->year = $year;
			$this->view->college = $college;
				
			if(count($inputChains->getMessages()) > 0) //数据有问题
			{
				$this->view->err_tip = $inputChains->getMessages();
			}
				
			else //插入数据库
			{
				$data = array(
					'class_name' => $name,
					'class_year' => $year,
					'class_create_time' => time(),
					'class_college' => $college,
					'class_charge' => $this->view->passport('uid'),
				);
				if(!$class_id = DbModel::initClass($data))
					$this->view->err_tip['class_name'] = '班级数据初始化失败';
				else 
				{
					// 建立班级文件目录
					@mkdir($_SERVER['DOCUMENT_ROOT'].'/static/classes/'.$class_id, 0777);
					// 将sessClass清除,重新分配
					$this->_sessClass->data = null;
					$this->view->suc_tip = '班级建立成功,2秒后转向<a href="/class/home?c='.$class_id.'">班级主页</a>';
					Cmd::cacheClass($this->view->passport('uid'));
					echo Commons::js_jump('/class/home?c='.$class_id,2);
				}
			}
		}
	}
}
