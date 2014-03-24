<?php

echo '<h1>Mixinable Class Test</h1>';

require_once './TestClass1.php';
$test1 = new TestClass1();

// calling a mixed in function
echo '<strong>Calling "$test1->myMixedInFunction();"</strong><br />';
try{
    $value = $test1->myMixedInFunction('Hello', 'World');
    echo '<em>Returned value from myMixedInFunction = '.$value.'</em><br />';
}
catch(Exception $e){
    echo $e->getMessage().'<br />';
}
echo '<br />';

// calling a non existant function
echo '<strong>Calling "$test1->thisFunctionDoesNotExist();"</strong><br />';
try{
    $test1->thisFunctionDoesNotExist();
}
catch(Exception $e){
    echo $e->getMessage().'<br />';
}
echo '<br />';

require_once './TestClass2.php';
$test2 = new TestClass2();

// calling a mixed in function
echo '<strong>Calling "$test2->myMixedInFunction();"</strong> (Note: TestClass2 has two classes within the mixin callback chain.)<br />';
try{
    $value = $test2->myMixedInFunction('Hello', 'World');
    echo '<em>Returned value from myMixedInFunction = '.$value.'</em><br />';
}
catch(Exception $e){
    echo $e->getMessage().'<br />';
}
echo '<br />';

// calling a non existant function
echo '<strong>Calling "$test2->thisFunctionDoesNotExist();"</strong><br />';
try{
    $test2->thisFunctionDoesNotExist();
}
catch(Exception $e){
    echo $e->getMessage().'<br />';
}
echo '<br />';
