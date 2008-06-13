<?php

class Zend_View_Helper_Coin
{
    function coin($uid)
    {
        $User = new UserModel();
        $row = $User->fetchRow('user_id = '.$uid);
        return $row->user_coin;
    }
}

?>