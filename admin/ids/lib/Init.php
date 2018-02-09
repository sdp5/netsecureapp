<?php


// This class is used for the purpose to initiate the framework and inhabits
// functionality to parse the needed configuration file.

class IDS_Init
{

    public $config = array();
    private static $instances = array();
    private $configPath = null;
    private function __construct($configPath = null) 
    {
        include_once 'lib/Monitor.php';
        include_once 'lib/Filter/Storage.php';

        if ($configPath) {
            $this->setConfigPath($configPath);
            $this->config = parse_ini_file($this->configPath, true);
        }
    }

    public final function __clone() 
    {
    }

    public static function init($configPath = null)
    {
        if (!isset(self::$instances[$configPath])) {
            self::$instances[$configPath] = new IDS_Init($configPath);
        }

        return self::$instances[$configPath];
    }

    public function setConfigPath($path) 
    {
        if (file_exists($path)) {
            $this->configPath = $path;
        } else {
            throw new Exception(
                'Configuration file could not be found at ' .
                htmlspecialchars($path, ENT_QUOTES, 'UTF-8')
            );
        }
    }

    public function getConfigPath() 
    {
        return $this->configPath;
    }
    public function getBasePath() {
    	
    	return ((isset($this->config['General']['base_path']) 
            && $this->config['General']['base_path'] 
            && isset($this->config['General']['use_base_path']) 
            && $this->config['General']['use_base_path']) 
                ? $this->config['General']['base_path'] : null);
    }

	public function setConfig(array $config, $overwrite = false) 
    {
        if ($overwrite) {
            $this->config = $this->_mergeConfig($this->config, $config);
        } else {
            $this->config = $this->_mergeConfig($config, $this->config);
        }
    }

    protected function _mergeConfig($current, $successor)
    {
        if (is_array($current) and is_array($successor)) {
            foreach ($successor as $key => $value) {
                if (isset($current[$key])
                    and is_array($value)
                    and is_array($current[$key])) {

                    $current[$key] = $this->_mergeConfig($current[$key], $value);
                } else {
                    $current[$key] = $successor[$key];
                }
            }
        }
        return $current;
    }

    public function getConfig() 
    {
        return $this->config;
    }
}