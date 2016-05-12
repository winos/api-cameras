<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Define main directory application
 */

define('APP_DIR', dirname(__FILE__) . '/application');
define('DS', DIRECTORY_SEPARATOR);

/**
 * Step 1: Require the Slim Framework
 */

require APP_DIR . '/vendor/Slim/Slim.php';
require APP_DIR . '/vendor/RedBeanPHP/rb.php';

\Slim\Slim::registerAutoloader();

/**
 * Implementacion de autocarga para controladores,
 * modelos y librerias que usamos en nuestra aplicacion.
 */
function resources_autoload($class_name) {
    if (is_readable(APP_DIR . '/controller/' . $class_name . '.php')) {
        require APP_DIR . '/controller/' . $class_name . '.php';
    } else if (is_readable(APP_DIR . '/model/' . $class_name . '.php')) {
        require APP_DIR . '/model/' . $class_name . '.php';
    }
}

spl_autoload_register('resources_autoload');

$app = new \Slim\Slim();

/**
 * Hacemos la conexion a la base de datos y
 * inicializamos algunas variables
 */
$app->hook('slim.before', function () use ($app) {
    // Cargar configuracion
    $conf = parse_ini_file(APP_DIR . '/config/application.ini', true);
    $db = $conf['db'];
    $database_dsn = sprintf('mysql:host=%s;dbname=%s',
        $db['hostname'], $db['dbname']);
    R::setup($database_dsn, $db['username'], $db['password']);
//    R::debug(true);
});

$app->hook('slim.after', function () use ($app) {
    R::close();
});

$ruta = isset($_GET['ruta']) ? $_GET['ruta'] : null ;
if (!$ruta) {
  $app->get('/', array(new \NearCamera_Controller, 'get'));
} else {
  $app->get('/', array(new \Devices_Controller, 'get'));
}

$app->run();
