<?php

//  Copyright Oliver Lillie 2010-2013
//  $Id$ $Rev$

require_once './MixinableTrait.php';
require_once './MixedInClass1.php'; // normally done by an autoloader or somesuch
require_once './MixedInClass2.php'; // normally done by an autoloader or somesuch

class TestClass2{

    use MixinableTrait;

    protected $_my_protected_property;

    public function __construct()
    {
        $this->_my_protected_property = 'abcdefg';

        // methods are explicity set to prevent un-needed overhead of file inclusion and funciton mapping.
        $this->addMethodsToMixinCallStack(array(
            'MixedInClass1' => array('myMixedInFunction'),
            'MixedInClass2' => array('myMixedInFunction'),
        ));
    }

    public function thisFunctionDoesNotExist()
    {
        echo 'Actually this function does exist in TestClass2 as a native class function.<br />';
        echo '<em>Called within class: <strong>'.get_class($this).'</strong></em><br />';
    }

}
