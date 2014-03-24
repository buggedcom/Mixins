<?php

//  Copyright Oliver Lillie 2010-2013
//  $Id$ $Rev$

/**
* An abstract class that allows the creation of mixinable class methods.
* Kudos to Schleicher for already having solved the property access problems.
*
* @package default
* @author buggedcom
* @author Schleicher
* @link http://www.schleicher.ru/blog/183.html
* @see aMixinable
**/
class MixinBase extends ArrayObject
{
    private $_caller;

    public function __construct($caller)
    {
        $this->_caller = $caller;
    }

    public function &__get($prop)
    {
        return $this->_caller->__accessGetProperty($prop);
    }

    public function __set($prop, $value)
    {
        return $this->_caller->__accessSetProperty($prop, $value);
    }

    public function __call($method, $args)
    {
        return $this->_caller->__accessCall($method, $args);
    }
}
