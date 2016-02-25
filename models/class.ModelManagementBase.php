<?php
class NBWModelManagementBase {
    protected $_last_error;

    /**
     * To use internally by derived class. Error can be a string or an exception object
     * @param $error
     */
    protected function set_last_error($error) {
        if ($error instanceof \Exception) {
            /* @var $error \Exception */
            $msg = $error->getMessage();
            $trace = $error->getTraceAsString();
            $file = $error->getFile();
            $line = $error->getLine();
            error_log("$msg \n. $trace at \n $file : $line");
            $msg = $error->getMessage();
        } else {
            $msg = $error;
        }
        $this->_last_error = $msg;
    }

    /**
     * Get last error recorded by set_last_error
     * @return mixed
     */
    public function get_last_error() {
        return $this->_last_error;
    }

    /**
     * Install a table to Wordpress database, deriving the structure from $sql query
     * @param $sql
     */
    protected function _install($sql) {
        global $wpdb;
        /* @var $wpdb wpdb */
        if ( ! empty( $wpdb->charset ) ) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if ( ! empty( $wpdb->collate ) ) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }

        $sql = $sql . ' ' . $charset_collate . ';';

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /**
     * Get one record of a table matching condition, map to an instance of type $class, with ignored field
     * @param $class
     * @param $table
     * @param $conditions
     * @param array $ignored_fields
     * @return null
     */
    protected function _get_one($class, $table, $conditions, $ignored_fields = array()) {
        global $wpdb;
        /* @var $wpdb wpdb */

        $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $conditions . ' LIMIT 0, 1';

        $results = $wpdb->get_results($sql, ARRAY_A);
        if (count($results) >= 1) {
            $item = new $class();
            foreach($results[0] as $key => $value) {
                if (in_array($key, $ignored_fields)) continue;
                $item->$key = $value;
            }
            return $item;
        }

        return null;
    }

    /**
     * Get all records of a table matching conditions, and map to an instance of type $class, with optional pagination and ignored mapping fields
     * @param $class
     * @param string $select
     * @param string $sql
     * @param null $take
     * @param null $skip
     * @param array $ignored_fields
     * @return array
     */
    protected function _all($class, $select = '*', $sql = '', $take = null, $skip = null, $ignored_fields = array()) {
        global $wpdb;

        /* @var $wpdb wpdb */
        $total = 0;
        if ($take !== null && $skip !== null) {
            $count_sql = 'SELECT COUNT(*) ' . $sql;
            $total = $wpdb->get_var($count_sql);
            $sql .= ' LIMIT ' . $skip . ', ' . $take;
        }

        $results = $wpdb->get_results("SELECT $select " . $sql, ARRAY_A);
        $return = array();

        foreach($results as $record) {
            $item = new $class();
            foreach($record as $key => $value) {
                if (in_array($key, $ignored_fields)) continue;
                $item->$key = $value;
            }
            $return[] = $item;
        }

        if ($take !== null && $skip !== null) {
            return array(
                'total' => $total,
                'data' => $return,
                'next_skip' => $total + $skip - 1
            );
        }
        return array(
            'total' => count($return),
            'data' => $return,
            'next_skip' => -1
        );
    }
}