<?php

require_once 'lib/Log/Interface.php';

// The database wrapper is designed to store reports into an sql database.

class IDS_Log_Database implements IDS_Log_Interface
{

    private $wrapper = null;
    private $user = null;
    private $password = null;
    private $table = null;
    private $handle    = null;
    private $statement = null;
    private $ip = 'local/unknown';
    private static $instances = array();
    protected function __construct($config) 
    {

        if ($config instanceof IDS_Init) {
            $this->wrapper  = $config->config['Logging']['wrapper'];
            $this->user     = $config->config['Logging']['user'];
            $this->password = $config->config['Logging']['password'];
            $this->table    = $config->config['Logging']['table'];

        } elseif (is_array($config)) {
            $this->wrapper  = $config['wrapper'];
            $this->user     = $config['user'];
            $this->password = $config['password'];
            $this->table    = $config['table'];
        }

        // determine correct IP address
        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        try {
            $this->handle = new PDO(
                $this->wrapper,
                $this->user,
                $this->password
            );

            $this->statement = $this->handle->prepare('
                INSERT INTO ' . $this->table . ' (
                    name,
                    value,
                    page,
                    ip,
                    impact,
                    origin,
                    created
                )
                VALUES (
                    :name,
                    :value,
                    :page,
                    :ip,
                    :impact,
                    :origin,
                    now()
                )
            ');
			echo "Log registered in database!!";

        } catch (PDOException $e) {
            die('PDOException: ' . $e->getMessage());
        }
    }

    public static function getInstance($config, $classname = 'IDS_Log_Database')
    {
        if ($config instanceof IDS_Init) {
            $wrapper = $config->config['Logging']['wrapper'];
        } elseif (is_array($config)) {
            $wrapper = $config['wrapper'];
        }

        if (!isset(self::$instances[$wrapper])) {
            self::$instances[$wrapper] = new $classname($config);
        }

        return self::$instances[$wrapper];
    }

    private function __clone() 
    { 
    }

    public function execute(IDS_Report $data) 
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
            if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) { 
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
            } 
        }     	

        foreach ($data as $event) {
            $page = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $ip   = $this->ip;
            
            $name   = $event->getName();
            $value  = $event->getValue();
            $impact = $event->getImpact();

            $this->statement->bindParam('name', $name);
            $this->statement->bindParam('value', $value);
            $this->statement->bindParam('page', $page);
            $this->statement->bindParam('ip', $ip);
            $this->statement->bindParam('impact', $impact);
            $this->statement->bindParam('origin', $_SERVER['SERVER_ADDR']);

            if (!$this->statement->execute()) {

                $info = $this->statement->errorInfo();
                throw new Exception(
                    $this->statement->errorCode() . ', ' . $info[1] . ', ' . $info[2]
                );
            }
        }

        return true;
    }
}
