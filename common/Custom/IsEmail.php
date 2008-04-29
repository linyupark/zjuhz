<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_IsEmail.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_IsEmail extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const NOT_EMAIL = 'notEmail';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EMAIL => "'%value%' are not email",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array();

    /**
     * construct
     *
     * @return void
     */
    public function __construct()
    {
		// do it!
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is between min and max options, inclusively
     * if inclusive option is true.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $value))
		{
            $this->_error(self::NOT_EMAIL);
            return false;
        }

        return true;
    }
}
