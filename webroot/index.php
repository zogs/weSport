<?php

//$debut = microtime(true);

define('WEBROOT',dirname(__FILE__));
define('ROOT',dirname(WEBROOT));
define('DS',DIRECTORY_SEPARATOR);
define('CORE',ROOT.DS.'core');
define('BASE_URL',dirname(dirname($_SERVER['SCRIPT_NAME'])));



//include autoloader
//github https://github.com/jonathankowalski/autoload
include '../core/autoloader.php';
$loader = JK\Autoloader::getInstance()
->addDirectory('../config')
->addDirectory('../controller')
->addDirectory('../core')
->addDirectory('../model')
->addEntireDirectory('../lib');


//Libvrairy dependency
require '../lib/SwiftMailer/swift_required.php';


//define routes for the router
new Routes();

//launch the dispacher
new Dispatcher();

?>



<?php
//echo 'Page généré en '.round(microtime(true) - $debut,5).' secondes';
?>

