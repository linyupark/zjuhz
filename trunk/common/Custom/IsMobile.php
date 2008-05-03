<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_IsMobile.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_IsMobile extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const NOT_MOBILE = 'notMobile';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MOBILE => "'%value%' are not mobile",
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

        // 13xxxxxxxxx 15xxxxxxxxx
        if (!preg_match('/^1[3|5][0-9]{9}$/', $value))
		{
            $this->_error(self::NOT_MOBILE);
            return false;
        }

        return true;
    }
}
