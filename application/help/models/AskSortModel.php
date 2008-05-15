<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:AskSortModel.php
 */


/**
 * 校友互助-tbl_ask_sort
 * 表级操作类,含单表读/写/改等方法
 */
class AskSortModel //extends Zend_Db_Table_Abstract
{
    /**
     * 数据表名
     * @var string
     */
    protected $_name = 'tbl_ask_sort';

    /**
     * 数据表主键
     * @var string
     */
    protected $_primary = 'sid';

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
     * 更新分类拥有的数量
     * 
     * @param array $args
     * @return void
     */
	public function callCounter($args)
	{
		$this->_dao->prepare('CALL sp_sort_counter(:sort0, :sort1, :sort2, :filed);')
		           ->execute($args);
	}

    /**
     * 查找全部分类
     * 
     * @return array
     */
	public function selectAll()
    {
		return $this->_dao->fetchAll("SELECT * FROM {$this->_name};", array());
    }

    /**
     * 查找parent的子分类
     * 
     * @param integer $parent
     * @return array
     */
	public function selectParentPairs($parent)
    {
		return $this->_dao->fetchPairs("SELECT sid, name FROM {$this->_name} 
		    WHERE parent = :parent;", array('parent' => $parent)
		);
    }

	/**
     * 查找按分类自定义状态的全部问题
     * 
     * @param string $status
     * @param integer $sid
     * @param string $order
     * @param string $limit
     * @return array
     */
	public function selectQuestionAll($status, $sid, $order, $limit)
	{
		return $this->_dao->fetchAll("SELECT q.qid, q.title, q.offer, q.addTime, q.status, q.reply 
		    FROM tbl_ask_question AS q WHERE (q.status IN ({$status})) AND (q.sid IN (SELECT s.sid 
		    FROM tbl_ask_sort AS s WHERE sid = :sid OR pid = :pid OR parent = :parent)) 
		    ORDER BY {$order} LIMIT {$limit};", array('sid' => $sid, 'pid' => $sid, 'parent' => $sid)
		);
    }
}
