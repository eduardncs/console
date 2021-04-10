<?php
function __autoloader($classname) {
    $split = explode("\\",$classname);
    $filename = str_replace($split[0].'\\', $_SERVER['DOCUMENT_ROOT'].'/productieproprie/system/', $classname) . '.class.php';
    require_once $filename;
}

// register the autoloader
spl_autoload_register('__autoloader');
?>