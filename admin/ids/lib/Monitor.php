<?php

// This class represents the core of the frameworks attack detection mechanism
// and provides functions to scan incoming data for malicious appearing script
// fragments.

class IDS_Monitor
{

    private $tags = null;
    private $request = null;
    private $storage = null;
    private $report = null;
    public $scanKeys = false;
    private $exceptions = array();
    private $html = array();
    private $htmlpurifier = NULL;
    private $pathToHTMLPurifier = '';
    private $HTMLPurifierCache = '';

    public function __construct(array $request, IDS_Init $init, array $tags = null)
    {
        $version = isset($init->config['General']['min_php_version'])
            ? $init->config['General']['min_php_version'] : '5.1.6';

        if (version_compare(PHP_VERSION, $version, '<')) {
            throw new Exception(
                'PHP version has to be equal or higher than ' . $version . ' or
                PHP version couldn\'t be determined'
            );
        }


        if (!empty($request)) {
            $this->storage = new IDS_Filter_Storage($init);
            $this->request = $request;
            $this->tags    = $tags;

            $this->scanKeys   = $init->config['General']['scan_keys'];

            $this->exceptions = isset($init->config['General']['exceptions'])
                ? $init->config['General']['exceptions'] : false;

            $this->html       = isset($init->config['General']['html'])
                ? $init->config['General']['html'] : false;

            $this->json       = isset($init->config['General']['json'])
                ? $init->config['General']['json'] : false;

            if(isset($init->config['General']['HTML_Purifier_Path'])
                && isset($init->config['General']['HTML_Purifier_Cache'])) {
                
                $this->pathToHTMLPurifier = 
                    $init->config['General']['HTML_Purifier_Path'];
                
                $this->HTMLPurifierCache  = $init->getBasePath()
                    . $init->config['General']['HTML_Purifier_Cache'];
            }

        }

        if (!is_writeable($init->getBasePath()
            . $init->config['General']['tmp_path'])) {
            throw new Exception(
                'Please make sure the ' . 
                htmlspecialchars($init->getBasePath() . 
                $init->config['General']['tmp_path'], ENT_QUOTES, 'UTF-8') . 
                ' folder is writable'
            );
        }

        include_once 'lib/Report.php';
        $this->report = new IDS_Report;
    }

    public function run()
    {
        if (!empty($this->request)) {
            foreach ($this->request as $key => $value) {
                $this->_iterate($key, $value);
            }
        }

        return $this->getReport();
    }

    private function _iterate($key, $value)
    {

        if (!is_array($value)) {
            if (is_string($value)) {

                if ($filter = $this->_detect($key, $value)) {
                    include_once 'lib/Event.php';
                    $this->report->addEvent(
                        new IDS_Event(
                            $key,
                            $value,
                            $filter
                        )
                    );
                }
            }
        } else {
            foreach ($value as $subKey => $subValue) {
                $this->_iterate($key . '.' . $subKey, $subValue);
            }
        }
    }

	private function _detect($key, $value)
    {
        
        // define the pre-filter
        $prefilter = '/[^\w\s\/@!?\.]+|(?:\.\/)|(?:@@\w+)/';
        
        // to increase performance, only start detection if value
        // isn't alphanumeric
        if (!$this->scanKeys 
            && (!$value || !preg_match($prefilter, $value))) {
            return false;
        } elseif($this->scanKeys) {
            if((!$key || !preg_match($prefilter, $key)) 
                && (!$value || !preg_match($prefilter, $value))) {
                return false;
            }
        }

        // check if this field is part of the exceptions
        if (is_array($this->exceptions)
            && in_array($key, $this->exceptions, true)) {
            return false;
        }

        // check for magic quotes and remove them if necessary
        if (function_exists('get_magic_quotes_gpc')
            && get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

        // if html monitoring is enabled for this field - then do it!
        if (is_array($this->html) && in_array($key, $this->html, true)) {
            list($key, $value) = $this->_purifyValues($key, $value);
        }

        // use the converter
        include_once 'lib/Converter.php';
        $value = IDS_Converter::runAll($value);
        $value = IDS_Converter::runCentrifuge($value, $this);

        // scan keys if activated via config
        $key = $this->scanKeys ? IDS_Converter::runAll($key)
            : $key;
        $key = $this->scanKeys ? IDS_Converter::runCentrifuge($key, $this)
            : $key;

        $filters   = array();
        $filterSet = $this->storage->getFilterSet();
        foreach ($filterSet as $filter) {

            if (is_array($this->tags)) {
                if (array_intersect($this->tags, $filter->getTags())) {
                    if ($this->_match($key, $value, $filter)) {
                        $filters[] = $filter;
                    }
                }
            } else {
                if ($this->_match($key, $value, $filter)) {
                    $filters[] = $filter;
                }
            }
        }

        return empty($filters) ? false : $filters;
    }


    private function _purifyValues($key, $value) 
    {
        /*
         * Perform a pre-check if string is valid for purification
         */
        if(!$this->_purifierPreCheck($key, $value)) {
            return array($key, $value);
        }

        include_once $this->pathToHTMLPurifier;

        if (!is_writeable($this->HTMLPurifierCache)) {
            throw new Exception(
                $this->HTMLPurifierCache . ' must be writeable');
        }

        if (class_exists('HTMLPurifier')) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Attr.EnableID', true);
            $config->set('Cache.SerializerPath', $this->HTMLPurifierCache);
            $config->set('Output.Newline', "\n");
            $this->htmlpurifier = new HTMLPurifier($config);
        } else {
            throw new Exception(
                'HTMLPurifier class could not be found - ' .
                'make sure the purifier files are valid and' .
                ' the path is correct'
            );
        }

        $purified_value = $this->htmlpurifier->purify($value);
        $purified_key   = $this->htmlpurifier->purify($key);

        $redux_value = strip_tags($value);
        $redux_key   = strip_tags($key);

        if ($value != $purified_value || $redux_value) {
            $value = $this->_diff($value, $purified_value, $redux_value);
        } else {
            $value = NULL;
        }
        if ($key != $purified_key) {
            $key = $this->_diff($key, $purified_key, $redux_key);
        } else {
            $key = NULL;
        }

        return array($key, $value);
    }
    
    private function _purifierPreCheck($key = '', $value = '') 
    {
        $tmp_value = preg_replace('/\p{C}/', null, $value);
        $tmp_key = preg_replace('/\p{C}/', null, $key);
        
        $precheck = '/<(script|iframe|applet|object)\W/i';
        if(preg_match($precheck, $tmp_key) 
            || preg_match($precheck, $tmp_value)) {
            
            return false;
        }
        return true;
    }
    

    private function _diff($original, $purified, $redux)
    {
        $purified = preg_replace('/\s+alt="[^"]*"/m', null, $purified);
        $purified = preg_replace('/=?\s*"\s*"/m', null, $purified);

        $original = preg_replace('/=?\s*"\s*"/m', null, $original);
        $original = preg_replace('/\s+alt=?/m', null, $original);

        // check which string is longer
        $length = (strlen($original) - strlen($purified));
        if ($length > 0) {
            $array_2 = str_split($original);
            $array_1 = str_split($purified);
        } else {
            $array_1 = str_split($original);
            $array_2 = str_split($purified);
        }
        foreach ($array_2 as $key => $value) {
            if ($value !== $array_1[$key]) {
                $array_1   = array_reverse($array_1);
                $array_1[] = $value;
                $array_1   = array_reverse($array_1);
            }
        }

        // return the diff - ready to hit the converter and the rules
        $diff = trim(join('', array_reverse(
            (array_slice($array_1, 0, $length)))));

        // clean up spaces between tag delimiters
        $diff = preg_replace('/>\s*</m', '><', $diff);

        // correct over-sensitively stripped bad html elements
        $diff = preg_replace('/[^<](iframe|script|embed|object' .
            '|applet|base|img|style)/m', '<$1', $diff);

        if ($original == $purified && !$redux) {
            return null;
        }

        return $diff . $redux;
    }

    private function _match($key, $value, $filter)
    {
        if ($this->scanKeys) {
            if ($filter->match($key)) {
                return true;
            }
        }

        if ($filter->match($value)) {
            return true;
        }

        return false;
    }

    public function setExceptions($exceptions)
    {
        if (!is_array($exceptions)) {
            $exceptions = array($exceptions);
        }

        $this->exceptions = $exceptions;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function setHtml($html)
    {
        if (!is_array($html)) {
            $html = array($html);
        }

        $this->html = $html;
    }

    public function addHtml($value)
    {
        $this->html[] = $value;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function getReport()
    {
        if (isset($this->centrifuge) && $this->centrifuge) {
            $this->report->setCentrifuge($this->centrifuge);
        }

        return $this->report;
    }

}