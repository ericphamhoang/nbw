<?php
/**
 * This class is mainly for Page Template management as well as appending custom assets into the site
 * User: nkahoang
 * Date: 8/07/2014
 * Time: 8:38 PM
 */

class NBWRouter {

    public static $prefix = "/nbw";
    public static $routes = array(
        '/' => array(
            "controller" => "Main",
            "action" => "index"
        ),
    );
    public static function processRequest() {
        $uri = str_replace('//','/',$_SERVER["REQUEST_URI"]);
        if (NBWUtils::start_with($uri, self::$prefix, false)) {
            $uri = str_replace(self::$prefix, "", $uri);

            foreach(self::$routes as $pattern => $route) {
                $matches = array();
                if (preg_match('|^' . $pattern . '/?(\?.*)?$|', $uri, $matches)) {
                    array_shift($matches);
                    self::executeAction($route, $matches);
                }
            }
        }
    }

    public static function executeAction($route, $args = array()) {
        $controller = self::loadController($route["controller"]);

        if ($controller) {
            $action = (isset($route["action"])?$route["action"]:"index") . "_" . strtolower($_SERVER["REQUEST_METHOD"]);
            call_user_func_array(array($controller, $action), $args);
            if (!isset($route['continue']) || !filter_var($route['continue'],FILTER_VALIDATE_BOOLEAN)) {
                exit();
            }
        }
    }

    public static function loadController($controller) {
        $controllerClass = $controller . "Controller";
        require_once (NBW__PLUGIN_DIR . '/controllers/' . $controllerClass . '.php');
        return new $controllerClass();
    }
} 