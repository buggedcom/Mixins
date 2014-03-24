<?php

//  Copyright Oliver Lillie 2010-2013
//  $Id$ $Rev$

/**
 * The base class to allow a mixinable class structure.
 * All Entity based objects are mixinable.
 *
 * @package default
 * @author Oliver Lillie
 **/
trait MixinableTrait
{
    protected $_mixin_methods = array();
    protected $_mixins = array();
    protected $_properties = false;
    
    public function &__accessGetProperty($prop)
    {
        if($this->_properties === false)
        {
            $this->_properties = array_flip(array_keys(get_class_vars(get_class($this))));
        }
        if(isset($this->_properties[$prop]) === false)
        {
            $val = $this->__get($prop);
            return $val;
        }
        return $this->{$prop};
    }

    public function __accessSetProperty($prop, $value)
    {
        if($this->_properties === false)
        {
            $this->_properties = array_flip(array_keys(get_class_vars(get_class($this))));
        }
        if(isset($this->_properties[$prop]) === false)
        {
             return $this->__set($prop, $value);
        }
        return $this->{$prop} = $value;
    }

    public function __accessCall($method, $value)
    {
        return call_user_func_array(array($this, $method), $value);
    }
    
    public function callMixin($method)
    {
        $args = func_get_args();
        array_shift($args);
        $trace = debug_backtrace();
        $parent_class = null;
        foreach ($trace as $key => $dump)
        {
            if(isset($dump['class']) === true && isset($dump['function']) === true)
            {
                if($dump['class'] !== 'MixinAbstract' && $dump['function'] === 'callMixin')
                {
                    $parent_class = $dump['class'];
                    break;
                }
            }
        }
        unset($trace);
        return $this->__call($method, $args, $parent_class);
    }
    
    public function parentClass($method)
    {
        $args = func_get_args();
        array_shift($args);
        if(method_exists(get_parent_class($this), $method) === true)
        {
            return call_user_func_array(array($this, 'parent::'.$method), $args);
        }
        return call_user_func_array(array($this, $method), $args);
    }
    
    /**
     * Adds mixin class and function methods to the mixin call stack.
     *
     * @author Oliver Lillie
     * @access public
     * @param mixed $mixin_class
     * @param mixed $method
     * @return void
     */
    public function addMethodsToMixinCallStack($mixin_class, $method=null)
    {
//      We purposely do not validate anything here in order to improve performance
        if(is_array($mixin_class) === false)
        {
            if($method === null)
            {
                throw new InvalidArgumentException('`$method` argument given to '.get_class($this).'::addMethodsToMixinCallStack cannot be null.', array(get_class($this)));
            }
            $mixin_class = array($mixin_class=>$method);
        }
        
//      loop process and add to the call stack.
        foreach ($mixin_class as $class => $method)
        {
            if(is_array($method) === false)
            {
                $method = array($method);
            }
            foreach($method as $func)
            {
                if(isset($this->_mixin_methods[$func]) === false)
                {
                    $this->_mixin_methods[$func] = array();
                }
                array_push($this->_mixin_methods[$func], $class);
            }
        }
    }

    public function __call($method, $arguments=array())
    {
//      determine if the __call is being called from within another mixin class.
        $from_mixin_class = null;
        if(func_num_args() === 3)
        {
            $args = func_get_args();
            $from_mixin_class = array_pop($args);
        }
        if(isset($this->_mixin_methods[$method]) === false)
        {
            throw new BadMethodCallException('The method "'.$method.'" does not exist within the class "'.get_class($this).'".');
        }
        
//      make sure the class that is calling the mixin function is supplied as the first argument
//      given to the called function.
        array_unshift($arguments, $this);
        
        $return = null;
        $execute = false;
        foreach($this->_mixin_methods[$method] as $key=>$class)
        {
//          this part of the execution chain ensures that the callMixin methods only call down the chain of mixins
            if($from_mixin_class !== null)
            {
                if($execute === false)
                {
                    if($from_mixin_class === $class)
                    {
                        $execute = true;
                    }
                    continue;
                }
            }
            
            if(method_exists($class, $method) === false)
            {
                throw new BadMethodCallException('Method "'.$method.'" does not appear to be executable from within the extending MixinBase class "'.$class.'".');
            }
            
            if(isset($this->_mixins[$class]) === false)
            {
                $this->_mixins[$class] = new $class($this);
            }
            $return = call_user_func_array(array($this->_mixins[$class], $method), $arguments);
        }

        return $return;
    }
}
