<?php


global $CFG;
require_once $CFG->libdir.'/htmlpurifier/HTMLPurifier.safe-includes.php';
require_once $CFG->libdir.'/htmlpurifier/locallib.php';

class mustache_sanitize_helper {
    private HTMLPurifier $purifier;
    public function __construct() {
        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
    }
    public function sanitize_html($raw_html, Mustache_LambdaHelper $helper) {
        return $this->purifier->purify($raw_html);
    }
}
