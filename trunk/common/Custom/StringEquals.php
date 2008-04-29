<?php

/**
 * [Custom] (C)2008 zjuhz.com
 * 
 * $File : Zend_Validate_StringEquals.php $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend_Extends
 * @package    Zend_Validate
 */
class Zend_Validate_StringEquals extends Zend_Validate_Abstract
{
	// constants for defining what currency symbol should be displayed
    const NOT_EQUALS = 'notEquals';
    const IS_NULL = 'isNull';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_EQUALS => "'%value%' are not equals",
        self::IS_NULL => "'%value%' is null",
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
     * Returns the input option
     *
     * @return mixed
     */
    public function getInput()
    {
        return $this->_input;
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

        if (is_null($this->_input) || is_null($this->_value))
        {
            $this->_error(self::IS_NULL);
            return false;
        }

        if (strcmp($this->_input,$this->_value))
        {
            $this->_error(self::NOT_EQUALS);
            return false;            
        }

        return true;
    }    
}
