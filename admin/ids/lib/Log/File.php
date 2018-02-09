<?php

require_once 'lib/Log/Interface.php';

// The file wrapper is designed to store data into a flatfile. It implements the
// singleton pattern.

class IDS_Log_File implements IDS_Log_Interface
{

    private $logfile = null;
    private static $instances = array();
    private $ip = 'local/unknown';

	protected function __construct($logfile) 
    {

        // determine correct IP address
        if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $this->ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $this->logfile = $logfile;
    }

    public static function getInstance($config, $classname = 'IDS_Log_File') 
    {
        if ($config instanceof IDS_Init) {
            $logfile = $config->getBasePath() . $config->config['Logging']['path'];
        } elseif (is_string($config)) {
            $logfile = $config;
        }
        
        if (!isset(self::$instances[$logfile])) {
            self::$instances[$logfile] = new $classname($logfile);
        }

        return self::$instances[$logfile];
    }

    private function __clone() 
    { 
    }

    protected function prepareData($data) 
    {

        $format = '"%s",%s,%d,"%s","%s","%s","%s"';

        $attackedParameters = '';
        foreach ($data as $event) {
            $attackedParameters .= $event->getName() . '=' .
                rawurlencode($event->getValue()) . ' ';
        }

        $dataString = sprintf($format,
                              $this->ip,
                              date('c'),
                              $event->getImpact(),
                              join(' ', $data->getTags()),
                              trim($attackedParameters),
                              urlencode($_SERVER['REQUEST_URI']),
                              $_SERVER['SERVER_ADDR']);

        return $dataString;
    }

    public function execute(IDS_Report $data) 
    {

        $data = $this->prepareData($data);

        if (is_string($data)) {

            if (file_exists($this->logfile)) {
                $data = trim($data);

                if (!empty($data)) {
                    if (is_writable($this->logfile)) {

                        $handle = fopen($this->logfile, 'a');
                        fwrite($handle, trim($data) . "\n");
                        fclose($handle);

                    } else {
                        throw new Exception(
                            'Please make sure that ' . $this->logfile . 
                                ' is writeable.'
                        );
                    }
                }
            } else {
                throw new Exception(
                    'Given file does not exist. Please make sure the
                    logfile is present in the given directory.'
                );
            }
        } else {
            throw new Exception(
                'Please make sure that data returned by
                IDS_Log_File::prepareData() is a string.'
            );
        }

        return true;
    }
}