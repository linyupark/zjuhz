<?php

class CompanyAcl extends Zend_Controller_Plugin_Abstract
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
          ->addRole(new Zend_Acl_Role('member'), 'guest')
	  ->addRole(new Zend_Acl_Role('admin'));

      // 需要控制的资源 ....

      // 默认权限规则
      $Acl->allow(array('guest', 'member', 'admin'));
      // 设置控制规则 ....
      
      Zend_Registry::set('zendAcl', $Acl);
    }

    $this->_Acl = Zend_Registry::get('zendAcl');
  }

  public function preDispatch($_request)
  {
    // 当前访问者身份
    $role = Zend_Registry::get('sessCommon')->role;
    if (NULL === $role)
    {
      $role = 'guest';
      Zend_Registry::get('sessCommon')->role = $role;
    }

    // 获取控制器相关信息
    $module = $_request->module;
    $controller = $_request->controller;
    $action = $_request->action;

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
  }
}

?>
