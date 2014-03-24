<?php

//  Copyright Oliver Lillie 2010-2013
//  $Id$ $Rev$

require_once './MixinBase.php';

class MixedInClass1 extends MixinBase
{
    protected $_mixed_in_property = 0;

    public function myMixedInFunction($parent, $arg1, $arg2, $arg3=null)
    {
        $this->_mixed_in_property += 1;

        echo $arg1.' '.$arg2.'<br />';
        echo '<em>Called within class: <strong>'.get_class($parent).'</strong></em><br />';
        echo 'Protected property from parent class: '.$parent->__accessGetProperty('_my_protected_property').'<br />';
        echo 'Protected property from mixed-in class: '.$this->_mixed_in_property.'<br />';
        echo __FILE__.' on line# '.__LINE__.'<br />';

        return 'MixedInClass1';
    }
}
