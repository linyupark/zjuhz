<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_IsPhone.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_IsPhone extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const NOT_PHONE = 'notPhone';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_PHONE => "'%value%' are not phone",
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

        // 0571-12345678 021-12345678
        if (!preg_match('/^[0-9]{3,4}-[0-9]{7,8}$/', $value))
		{
            $this->_error(self::NOT_PHONE);
            return false;
        }

        return true;
    }
}
