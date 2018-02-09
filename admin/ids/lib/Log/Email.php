<?php

require_once 'lib/Log/Interface.php';

// The Email wrapper is designed to send reports via email. It implements the
// singleton pattern.

class IDS_Log_Email implements IDS_Log_Interface
{

    protected $recipients    = array();
    protected $subject = null;
    protected $headers = null;
    protected $safemode = true;
    protected $urlencode = true;
    protected $allowed_rate = 15;
    protected $tmp_path = 'IDS/tmp/';
    protected $file_prefix = 'IDS_Log_Email_';
    protected $ip = 'local/unknown';
    protected static $instance = array();

    protected function __construct($config)
    {

        if ($config instanceof IDS_Init) {
            $this->recipients   = $config->config['Logging']['recipients'];
            $this->subject      = $config->config['Logging']['subject'];
            $this->headers      = $config->config['Logging']['header'];
            $this->envelope     = $config->config['Logging']['envelope'];
            $this->safemode     = $config->config['Logging']['safemode'];
            $this->urlencode    = $config->config['Logging']['urlencode'];
            $this->allowed_rate = $config->config['Logging']['allowed_rate'];
            $this->tmp_path     = $config->getBasePath() 
                . $config->config['General']['tmp_path'];

        } elseif (is_array($config)) {
            $this->recipients[]      = $config['recipients'];
            $this->subject           = $config['subject'];
            $this->additionalHeaders = $config['header'];
        }

        // determine correct IP address and concat them if necessary
        $this->ip = $_SERVER['REMOTE_ADDR'] .
            (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
            ' (' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ')' : '');
    }

    public static function getInstance($config, $classname = 'IDS_Log_Email')
    {
        if (!self::$instance) {
            self::$instance = new $classname($config);
        }

        return self::$instance;
    }

    private function __clone()
    {
    }

    protected function isSpamAttempt()
    {

        $dir            = $this->tmp_path;
        $numPrefixChars = strlen($this->file_prefix);
        $files          = scandir($dir);
        foreach ($files as $file) {
            if (is_file($dir . $file)) {
                if (substr($file, 0, $numPrefixChars) == $this->file_prefix) {
                    $lastModified = filemtime($dir . $file);

                    if ((
                    time() - $lastModified) > 3600) {
                        unlink($dir . $file);
                    }
                }
            }
        }

        $remoteAddr = $this->ip;
        $userAgent  = $_SERVER['HTTP_USER_AGENT'];
        $filename   = $this->file_prefix . md5($remoteAddr.$userAgent) . '.tmp';
        $file       = $dir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($file)) {
            $handle = fopen($file, 'w');
            fwrite($handle, time());
            fclose($handle);

            return false;
        }

        $lastAttack = file_get_contents($file);
        $difference = time() - $lastAttack;
        if ($difference > $this->allowed_rate) {
            unlink($file);
        } else {
            return true;
        }

        return false;
    }

    protected function prepareData($data)
    {

        $format  = "The following attack has been detected by Netsecureapp\n\n";
        $format .= "IP: %s \n";
        $format .= "Date: %s \n";
        $format .= "Impact: %d \n";
        $format .= "Affected tags: %s \n";

        $attackedParameters = '';
        foreach ($data as $event) {
            $attackedParameters .= $event->getName() . '=' .
                ((!isset($this->urlencode) ||$this->urlencode) 
                	? urlencode($event->getValue()) 
                	: $event->getValue()) . ", ";
        }

        $format .= "Affected parameters: %s \n";
        $format .= "Request URI: %s \n";
        $format .= "Origin: %s \n";

        return sprintf($format,
                       $this->ip,
                       date('c'),
                       $event->getImpact(),
                       join(' ', $data->getTags()),
                       trim($attackedParameters),
                       urlencode($_SERVER['REQUEST_URI']),
                       $_SERVER['SERVER_ADDR']);
    }

    public function execute(IDS_Report $data)
    {

        if ($this->safemode) {
            if ($this->isSpamAttempt()) {
                return false;
            }
        }

        $data = $this->prepareData($data);

        if (is_string($data)) {
            $data = trim($data);

            // if headers are passed as array, we need to make a string of it
            if (is_array($this->headers)) {
                $headers = "";
                foreach ($this->headers as $header) {
                    $headers .= $header . "\r\n";
                }
            } else {
                $headers = $this->headers;
            }

            if (!empty($this->recipients)) {
                if (is_array($this->recipients)) {
                    foreach ($this->recipients as $address) {
                        $this->send(
                            $address,
                            $data,
                            $headers,
                            $this->envelope
                        );
                    }
                } else {
                    $this->send(
                        $this->recipients,
                        $data,
                        $headers,
                        $this->envelope
                    );
                }
            }

        } else {
            throw new Exception(
                'Please make sure that data returned by
                 IDS_Log_Email::prepareData() is a string.'
            );
        }

        return true;
    }

    protected function send($address, $data, $headers, $envelope = null)
    {
        if (!$envelope || strpos(ini_get('sendmail_path'),' -f') !== false) {
            return mail($address,
                $this->subject,
                $data,
                $headers);
        } else {
            return mail($address,
                $this->subject,
                $data,
                $headers,
                '-f' . $envelope);
        }
    }
}