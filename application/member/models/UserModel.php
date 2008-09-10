<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:UserModel.php
 */


/**
 * 校友中心-tbl_user
 * 表级操作类,含单表读/写/改等方法
 */
class UserModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $_name = 'tbl_user';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'uid';

    /**
     * 数据表访问
     * @var object
     */
    protected $_dao = null;

    /**
     * 构造方法
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_dao = Zend_Registry::get('dao');
    }

    /**
     * 析构方法
     * 
     * @return void
     */
	public function __destruct()
    {
    	$this->_dao->closeConnection();
    }

    /**
     * 会员账号注册
     * 返回新注的uid 若为0注册失败
     * 
     * @param array $args
     * @return integer
     */
	public function callRegister($args)
    {
		$this->_dao->prepare('CALL sp_register
		    (:username, :password, :realName, :eMail, :sex, :year, :college, :major, :regIp, :ikey, @uid);')
		    ->execute($args);

		return $this->_dao->query('SELECT @uid AS uid')->fetchColumn();
    }

    /**
     * 会员账号登录
     * 返回至少包含uid的信息数组 uid为0登录失败
     * 
     * @param array $args
     * @return array
     */
	public function callLogin($args)
    {
		$stmt = $this->_dao->prepare('CALL sp_login
		    (:username, :password, :lastIp);');
		$stmt->execute($args);

		return $stmt->fetch();
    }

    /**
     * 查找字段存在数量
     * 
     * @param string $field
     * @param string $value
     * @return integer
     */
	public function selectFieldCount($field, $value)
    {
    	return $this->_dao->fetchOne("SELECT COUNT({$this->_primary}) FROM {$this->_name} 
    	    WHERE {$field} = :field;", array('field' => $value)
        );
    }

    /**
     * 更新会员基本资料
     * 
     * @param array $args
     * @param integer $uid
     * @return integer
     */
	public function update($args, $uid)
    {
		return $this->_dao->update($this->_name, $args, 
		    $this->_dao->quoteInto("{$this->_primary} = ?", $uid)
		);
    }

    /**
     * 更新会员密码
     * 
     * @param array $args
     * @return integer
     */
	public function updatePassword($args)
    {
    	$stmt = $this->_dao->prepare("UPDATE {$this->_name} SET password = :password 
    	    WHERE uid = :uid AND password = :oldpassword;");
		$stmt->execute($args);

		return $stmt->rowCount();
    }
}
