<?php

class NBWTwigWrapper {
    private static $_twig_loaded = false;

    /**
     * Start Twig templating engine
     * @return bool
     */
    public static function init_twig() {
        if (!self::$_twig_loaded) {
            include_once NBW__PLUGIN_DIR . '/lib/Twig/Autoloader.php';
            Twig_Autoloader::register(true);
            self::$_twig_loaded = true;
        }
        return self::$_twig_loaded;
    }

    /**
     * Load Twig Template file
     * @param $name
     * @return null|string
     */
    public static function load_twig_template($name) {
        $template_folder = NBW__PLUGIN_DIR . '/templates/';
        $file = path_join($template_folder, $name . ".twig");
        if (file_exists($file) && is_readable($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    /**
     * Render html from twig's template file
     * @param $template_name
     * @param $model
     * @return null|string
     */
    public static function render_twig_from_file($template_name, $model) {
        if (!NBWUtils::end_with($template_name,".twig",false)) {
            $template_name .= ".twig";
        }
        $template_folder = NBW__PLUGIN_DIR . 'templates/';
        self::load_twig_engine();
        $env = new \Twig_Environment(new Twig_Loader_Filesystem(array($template_folder)));
        return $env->render($template_name, $model);
    }

    /**
     * Loading the twig classes
     */
    public static function load_twig_engine() {
        if (!class_exists('\Twig_Autoloader')) {
            include_once NBW__PLUGIN_DIR . '/lib/Twig/Autoloader.php';
            \Twig_Autoloader::register(true);
        }
    }
    /**
     * Render html from twig's template in string
     * @param $template
     * @param $model
     * @return string
     */
    public static function render_twig_from_string($template, $model) {
        self::load_twig_engine();
        $env = new \Twig_Environment(new Twig_Loader_String());
        return $env->render(do_shortcode($template), $model);
    }
}