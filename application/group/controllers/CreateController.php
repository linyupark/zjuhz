<?php

/**
 * 群组的创建 CreateController
 * 
 * @author zjuhz.com
 * @version 
 */

class CreateController extends Zend_Controller_Action {
	
	/**
	 * 创建的表单显示
	 */
	public function indexAction() 
	{
		if(UserModel::fetch($this->view->passport('uid'), 'group_coin') < 100)
		{
			$this->render('nocoin');
		}
		else
		{
			$this->view->headTitle('创建新群组');
			$this->view->state = UserModel::fetch($this->passport('uid'), 'group_state');
			$this->view->sorts = Zend_Registry::get('iniGroup')->sort->name->toArray();
			$this->view->college = Zend_Registry::get('iniConfig')->college->name->toArray();
			$this->view->profession = Zend_Registry::get('iniGroup')->profession->name->toArray();
			$this->view->job = Zend_Registry::get('iniGroup')->job->name->toArray();
		}
	}
	
	/**
	 * 对提交的表单数据进行操作
	 *
	 */
	public function doAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$valid = new Lp_Valid();
		$user_id = $this->view->passport('uid');
		$name = $valid->of($request->getPost('name'), 'name', '群组名称', 'trim|strip_tags|required');
		$url = $valid->of($request->getPost('url'), 'url', '个性化网址', 'trim|strip_tags|strtolower|aldash');
		$intro = $valid->of($request->getPost('intro'), 'intro', '群组介绍', 'trim|strip_tags|str_between[12,200]');
		$sort = $valid->of($request->getPost('sort'), 'sort', '群组分类', 'required');
		$province = null;$city = null;
		$profession = null;
		$company = null;
		$occupation = null;
		$year = null;
		$college = null;
		if($sort == '11') //同事会
		{
			$province = $valid->of($request->getPost('corp_province'), 'province', '企业所在省', 'required');
			$city = $valid->of($request->getPost('corp_city'), 'city', '企业所在市', 'required');
			$profession = $valid->of($request->getPost('corp_profession'), 'profession', '企业所属行业', 'required');
			$company = $valid->of($request->getPost('company'), 'company', '企业名称', 'trim|strip_tags|required');
		}
		if($sort == '12') //同行会
		{
			$province = $valid->of($request->getPost('job_province'), 'province', '工作所在省', 'required');
			$city = $valid->of($request->getPost('job_city'), 'city', '工作所在市', 'required');
			$profession = $valid->of($request->getPost('job_profession'), 'profession', '工作所属行业', 'required');
			$occupation = $valid->of($request->getPost('job_occupation'), 'occupation', '职业名称', 'required');
		}
		if($sort == '13') //同学会
		{
			$year = $valid->of($request->getPost('class_year'), 'year', '入学年份', 'required');
			$college = $valid->of($request->getPost('class_college'), 'college', '所属学院', 'required');
		}
		$tags = $valid->of($request->getPost('tags'), 'tags', '群组标签', 'trim|strip_tags|required');
		$private = $valid->of($request->getPost('private'), 'private', '群组类型', 'required');
		// 校验失败
		if(false != $valid->getMessages())
		{
			$this->_helper->layout->setLayout('error');
			echo '<ul class="error span-13">'.$valid->getMessages('<li>','</li>').'</ul>';
		}
		// 入库前对群组url进行校验
		else
		{
			if(false != GroupModel::urlExist($url))
			{
				$this->_helper->layout->setLayout('error');
				echo '<ul class="error span-13"><li>个性化网址 '.$url.'已经被占用,请重新选择~</li></ul>';
			}
			else // 开始建立群组
			{
				$location = null;
				if($province != null && $city != null)
				{
					$location = $province.','.$city;
				}
				$data = array(
					'sort_id' => $sort,
					'master' => $user_id,
					'name' => $name,
					'url' => $url,
					'create_time' => time(),
					'intro' => $intro,
					'private' => $private,
					'tags' => $tags,
					'y_m_d' => date('Y-m-d'),
					'ext_location' => $location,
					'ext_trade' => $profession,
					'ext_year' => $year,
					'ext_college' => $college,
					'ext_job' => $occupation,
					'ext_corp' => $company
				);
				$group_id = GroupModel::insert($data);
				if(is_numeric($group_id) && $group_id > 0)
				{
					$data = array(
						'group_id' => $group_id,
						'user_id' => $user_id,
						'join_time' => time(),
						'last_access' => time(),
						'is_manager' => 1
					);
					GroupMemberModel::insert($data);
					GroupTagModel::insert($sort, $tags);
					UserModel::haveGroup($user_id);
					UserModel::coinMod($user_id, '-100');
					Cmd::createFolder($group_id);
					Cmd::flushGroupSession();
					// 显示成功信息
					$this->_helper->layout->setLayout('success');
					echo '<div class="success span-13">群组"'.$name.'"建立成功!即将转向<a href="/group/my">我的群组</a></div>';
					echo Commons::js_jump('/group/my',2);
				}
			}
		}
	}

}
