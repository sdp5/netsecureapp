<?php

require_once 'lib/Log/Interface.php';

// This class implements the composite pattern to allow to work with multiple logging wrappers at once.
 
class IDS_Log_Composite
{

    public $loggers = array();

    public function execute(IDS_Report $data) 
    {
    	// make sure request uri is set right on IIS
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
            if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) { 
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
            } 
        } 
        
        // make sure server address is set right on IIS
        if (isset($_SERVER['LOCAL_ADDR'])) {
            $_SERVER['SERVER_ADDR'] = $_SERVER['LOCAL_ADDR'];
        } 
    	
        foreach ($this->loggers as $logger) {
            $logger->execute($data);
        }
    }

	public function addLogger() 
    {

        $args = func_get_args();

        foreach ($args as $class) {
            if (!in_array($class, $this->loggers) && 
                ($class instanceof IDS_Log_Interface)) {
                $this->loggers[] = $class;
            }
        }
    }

    public function removeLogger(IDS_Log_Interface $logger) 
    {
        $key = array_search($logger, $this->loggers);

        if (isset($this->loggers[$key])) {
            unset($this->loggers[$key]);
            return true;
        }

        return false;
    }
}