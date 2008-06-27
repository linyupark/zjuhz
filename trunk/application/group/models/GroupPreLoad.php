<?php

class GroupPreLoad extends Zend_Controller_Plugin_Abstract
{
  /**
   * 全局可用的Zend_Acl对象
   *
   * @var object
   */
  private $_Acl;

  function __construct()
  {
    if (FALSE === Zend_Registry::isRegistered('zendAcl'))
    {
      $Acl = new Zend_Acl();

      // 用户组
      $Acl->addRole(new Zend_Acl_Role('guest'))
          ->addRole(new Zend_Acl_Role('member'))
	  	  ->addRole(new Zend_Acl_Role('admin'));

      // 需要控制的资源 ....
	  $Acl->add(new Zend_Acl_Resource('group_create'));
	  $Acl->add(new Zend_Acl_Resource('group_my'));
      // 默认权限规则
      $Acl->allow(array('guest', 'member', 'admin'));
      // 设置控制规则 ....
      $Acl->deny('guest', null, null);
      
      Zend_Registry::set('zendAcl', $Acl);
    }
    $this->_Acl = Zend_Registry::get('zendAcl');
  }

  public function preDispatch($_request)
  {
  	// 获取控制器相关信息
    $module = $_request->module;
    $controller = $_request->controller;
    $action = $_request->action;
  	// Layout初始化
  	Zend_Layout::startMvc(array('layoutPath' => '../../application/layouts/group/', 'layout' => 'default'));
  	// ajax请求自动关闭布局和渲染
  	if($_request->isXmlHttpRequest())
  	{
  		Zend_Layout::getMvcInstance()->disableLayout();
  		Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
  	}
	else
	{
		$this->getResponse()->insert('nav', '
		<div class="sub-nav span-13 last hrefspan-8" rel="'.$controller.'">
			<a href="/group/index">群组首页</a>
			<a href="/group/my">我的群组</a>
			<a href="/group/create">创建新群组</a>
		</div>
		');
	}
    // 当前访问者身份
    $role = Zend_Registry::get('sessCommon')->role;
    if (NULL === $role)
    {
      $role = 'guest';
      Zend_Registry::get('sessCommon')->role = $role;
    }
    // 资源标识
    $resource = $module.'_'.$controller;
    // 自动转换成资源
    if (FALSE === $this->_Acl->has($resource))
    {
      $this->_Acl->add(new Zend_Acl_Resource($resource));
    }
    // 权限判断
    if (FALSE === $this->_Acl->isAllowed($role, $resource, $action))
    {
      $_request->setControllerName('error');
      $_request->setActionName('relogin');
    }
    // 必要的用户数据初始化
    else 
    {
    	$passport = Zend_Registry::get('sessCommon')->login;
    	$isInit = Zend_Registry::get('sessGroup')->initGroup;
    	if(NULL == $isInit)
    	{
    		Zend_Registry::get('sessGroup')->initGroup = UserModel::init($passport);
    	}
    }
  }
}

?>
