<?php

/**
 * Class NBWTwigExtensions
 *
 * Extending Twig to include several popular Wordpress frontend helper as filter or function
 *
 * *** do_shortcode
 * *** wpautop
 * *** stripslashes
 */
class NBWTwigExtensions {

    const NBW_TWIG_FILTER_SHORTCODE = "shortcode";
    const NBW_TWIG_FILTER_WPAUTOP = "wpautop";
    const NBW_TWIG_FILTER_STRIPSLASHES = "stripslashes";

    private $filters_list;

    private $functions_list;

    public function __construct(){
        $this->filters_list = array();
        $this->functions_list = array();

        $this->initializeFilters();
        $this->initializeFunctions();
    }

    public function addFilter($name,$callable){
        if(is_callable($callable)){
            $twig_filter  = new Twig_SimpleFilter($name,$callable);
            $this->filters_list[$name] = $twig_filter;
        }
    }

    public function addFunction($name,$callable){
        if(is_callable($callable)){
            $twig_function = new Twig_SimpleFunction($name,$callable);
            $this->functions_list[$name] = $twig_function;
        }
    }

    public function loadFilter($filter_name){
        if(isset($this->filters_list[$filter_name]))
            return $this->filters_list[$filter_name];
        return null;
    }

    public function loadFunction($function_name){
        if(isset($this->functions_list[$function_name]))
            return $this->functions_list[$function_name];
        return null;
    }

    public static function shortcode_filter($value){
        return do_shortcode($value);
    }

    public static function stripslashes_filter($value){
        return stripslashes($value);
    }

    public static function wpautop_filter($value){
        return wpautop($value);
    }

    public function initializeFilters(){
        $constants = $this->getConstants();
        foreach($constants as $key=>$value){
            if(NBWUtils::start_with($key,"NBW_TWIG_FILTER_")){
                $method_name = $value."_filter";

                if(method_exists($this,$method_name))
                    $this->addFilter($value,array(__CLASS__,$method_name));
            }
        }
    }

    public function initializeFunctions(){
        $constants = $this->getConstants();
        foreach($constants as $key=>$value){
            if(NBWUtils::start_with($key,"NBW_TWIG_FUNCTION_")){
                $method_name = $value."_function";

                if(method_exists($this,$method_name))
                    $this->addFunction($value,array(__CLASS__,$method_name));

                $this->addFunction($value,array(__CLASS__,$method_name));
            }
        }
    }

    public function getConstants(){
        $rClass = new ReflectionClass(__CLASS__);
        return $rClass->getConstants();
    }
}