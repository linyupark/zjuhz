<?php

    class EventsModel extends Zend_Db_Table_Abstract
    {
        protected $_name = 'tbl_events';
        protected $_primary = 'event_id';
        
        public static function _dao($db = 'dbEvent')
        {
            self::setDefaultAdapter($db);
            return self::getDefaultAdapter();
        }
        
        // 参与的成员(报名/参与)
        public static function joinMembers($eid)
        {
            $db = self::_dao();
            return $db->fetchAll('SELECT `member`,`review`
                          FROM `tbl_events_member`
                          WHERE `event_id` = '.(int)$eid);
        }
        
        public static function join($eid)
        {
            $db = self::_dao();
            $db->insert('tbl_events_member', array('event_id' => $eid, 'member' => Cmd::myid()));
            $db->update('tbl_events',array('member_num'=>new Zend_Db_Expr('member_num + 1')), 'event_id = '.$eid);
        }
        
        public static function getOut($eid)
        {
            $db = self::_dao();
            $db->delete('tbl_events_member', 'event_id = '.$eid.' AND member = '.Cmd::myid());
            $db->update('tbl_events',array('member_num'=>new Zend_Db_Expr('member_num - 1')), 'event_id = '.$eid);
        }
    }