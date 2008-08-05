<?php

    class DevoteModel extends Zend_Db_Table_Abstract
    {
        protected $_name = 'tbl_transaction';
        protected $_primary = 'transaction_id';
        
        public static function _dao($db = 'dbDevote')
        {
            self::setDefaultAdapter($db);
            return self::getDefaultAdapter();
        }
    }