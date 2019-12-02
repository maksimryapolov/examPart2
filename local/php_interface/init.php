<?php
if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php";
}

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events.php";
}

if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/agent.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/agent.php";
}
if(file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/new_agent.php")) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/new_agent.php";
}