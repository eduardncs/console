<?php
function __autoloader($classname) {
    // $classname  will 'X\X' in the example
    $filename = str_replace('Rosance\\', $_SERVER['DOCUMENT_ROOT'].'/system/', $classname) . '.class.php';
    if (strpos($filename, 'Facebook') !== false) {
        require_once $_SERVER['DOCUMENT_ROOT'].'/system/'.$classname.'.php';
    }elseif(strpos($filename, 'Google') !== false)
        require_once $_SERVER['DOCUMENT_ROOT'].'/system/'.str_replace("Rosance\\","google/src/Google/",str_replace("Google_","",$classname)).'.php';
    else
        require_once $filename;
}

// register the autoloader
spl_autoload_register('__autoloader');
?>