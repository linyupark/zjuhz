<?php

    class Zend_View_Helper_Block
    {
        function block($db, $category, $limit)
        {
            $select = $db->select()->from(array('e' => 'tbl_entity'),array('entity_id','entity_title','entity_pub_time'));
            return $db->fetchAll($select->where('category_id = ?', $category)->where('entity_pub = ?',1)->order('entity_pub_time DESC')->limit($limit));
        }
    }

?>