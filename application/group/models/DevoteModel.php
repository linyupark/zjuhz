<?php

    class DevoteModel extends Zend_Db_Table_Abstract
    {
        protected $_name = 'tbl_transaction';
        protected $_primary = 'id';
        
        public static function _dao($db = 'dbDevote')
        {
            self::setDefaultAdapter($db);
            return self::getDefaultAdapter();
        }
    }