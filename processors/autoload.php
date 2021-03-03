<?php
function autoloader($classname) {
    // $classname  will 'X\X' in the example
    $classname  = str_ireplace("Rosance/","",str_ireplace('\\',"/", $classname));
    var_dump($classname);
    $filename = "../system/".$classname[1].'.class.php';
    require $filename;
}

// register the autoloader
spl_autoload_register('autoloader');
?>