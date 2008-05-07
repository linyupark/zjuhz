<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_NotEquals.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_NotEquals extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const EQUALS = 'equals';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::EQUALS => "'%value%' are equals",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array();

    /**
     * input
     *
     * @var string
     */
    protected $_input;


    /**
     * Sets validator options
     *
     * @param  mixed   $input
     * @return void
     */
    public function __construct($input = null)
    {
        $this->setInput($input);
    }

    /**
     * Sets the input option
     *
     * @param  mixed $input
     * @return Zend_Validate_StringEquals Provides a fluent interface
     */
    public function setInput($input)
    {
        $this->_input = $input;
        return $this;
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

        if ($this->_input == $this->_value)
        {
            $this->_error(self::EQUALS);
            return false;
        }

        return true;
    }    
}
