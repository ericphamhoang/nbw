<?php
class NBWBaseController {
    protected  $input = array();
    public function __construct() {
        $this->input = $_REQUEST;
        if (isset($_SERVER["CONTENT_TYPE"]) && strtolower($_SERVER["CONTENT_TYPE"] == "application/json")) {
            $raw_json = file_get_contents("php://input");
            try {
                $inputobj = json_decode($raw_json, true);
                foreach($inputobj as $key => $value) {
                    $this->input[$key] = $value;
                }
            }
            catch(\Exception $ex) {}
        }
    }

    /**
     * Get Wordpress's Database query object
     * @return wpdb
     */
    public function get_wp_query() {
        global $wpdb;
        /* @var $wpdb wpdb */
        return $wpdb;
    }

    /**
     * Render an object as json response, and optionally end the execution of the script
     * @param $object
     * @param bool $end_execution
     */
    public function render_json_response($object, $end_execution = true) {
        header("Content-type: application/json");
        echo json_encode($object);
        if ($end_execution) {
            exit;
        }
    }

    /**
     * Render an html using twig template, supplying models to the template
     * @param $object
     * @param $model
     * @param bool $end_execution
     */
    public function render_twig($template_name, $model, $end_execution = true) {
        NBWTwigWrapper::init_twig();
        echo NBWTwigWrapper::render_twig_from_file($template_name, $model);
        if ($end_execution) {
            exit;
        }
    }

    /**
     * Validate if current user is admin
     */
    public function validate_admin() {
        if (!current_user_can( 'manage_options' )) {
            header('HTTP/1.0 403 Forbidden');
            $this->to_json_result(array(
                "success" => false,
                "message" => "Unauthorized"
            ));
            exit;
        }
    }
}