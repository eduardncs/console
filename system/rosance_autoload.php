<?php
function ___autoloader($classname) {
    // $classname  will 'X\X' in the example
    $filename = str_replace('\\', '/', $classname) . '.class.php';
    require_once $filename;
}

// register the autoloader
spl_autoload_register('___autoloader');
?>