<?php
use CSY\EntryPoint;
use CSY2038\Routes;
require "../autoload.php";



$routes = new Routes();
//$routes->getPage();
$entry = new EntryPoint($routes);
$entry->run();






