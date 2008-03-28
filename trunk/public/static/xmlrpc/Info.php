<?php
require_once 'Zend/XmlRpc/Server.php';

/**
 * Return the MD5 sum of a value
 *
 * @param string $value Value to md5sum
 * @return string MD5 sum of value
 */
function md5Value($value)
{
    return md5($value);
}

$server = new Zend_XmlRpc_Server();
$server->addFunction('md5Value');
echo $server->handle();

