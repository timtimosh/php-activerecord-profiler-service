<?php

namespace ActiveRecordServiceProvider;

class Logger
{
    public $queries = array();
    private $_id = 0;

    /**
     * @param $query_or_params string|array sql query or sql query params
     * @see vendor/php-activerecord/php-activerecord/lib/Connection.php query() method
     */
    public function log($query_or_params, $execution_time = 0)
    {
        if (is_array($query_or_params)) {
            $this->queries[$this->_id]['params'] = $query_or_params;
            $this->queries[$this->_id]['prepared_statement'] = $this->expand_prepared_sql($this->queries[$this->_id]['sql'],$query_or_params);
        }
        else {
            $this->_id+=1;

            $this->queries[$this->_id]['sql'] = $query_or_params;
            $this->queries[$this->_id]['executionMS'] = number_format($execution_time, 5);
            $this->queries[$this->_id]['params'] = array();
            
        }
    }

    /**
     * Substitute the specified values into a prepared SQL statement
     * for logging purposes.
     *
     * @param string $sql SQL string containing placeholders for values.
     * @param array &$values Optional array of values to substitute into $sql.
     * @return string A copy of $sql with &$values substituted for placeholders.
     */
    private function expand_prepared_sql($sql, &$values = array())
    {
        if ($values) {
            $values = array_map(function($val)
            {
                if (is_null($val))
                    return 'NULL';
                else if (is_string($val))
                    return "'" . addslashes($val) . "'";
                else
                    return $val;
            }, $values);
            $sql = preg_replace_callback('/\?/', function($matches) use (&$values)
            {
                return array_shift($values);
            }, $sql);
        }
        return $sql;
    }

}
