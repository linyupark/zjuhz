<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_IsUrl.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_IsUrl extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const NOT_URL = 'notUrl';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_URL => "'%value%' are not url",
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

        if ('http://' != substr($value, 0, 7) || 11 >= strlen($value))
		{
            $this->_error(self::NOT_URL);
            return false;
        }

        return true;
    }
}
