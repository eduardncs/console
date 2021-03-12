<?php
header( "Content-type: application/json" );
require("main.class.php");
require("builder.class.php");
echo json_encode(
    [
        "Header" => HEADER,
        "MENU_BASE" => MENU_BASE,
        "MENU_TREE" => MENU_TREE
    ]
    );

?>