<?php

namespace core\output;

global $CFG;
require_once $CFG->libdir.'/htmlpurifier/HTMLPurifier.safe-includes.php';
require_once $CFG->libdir.'/htmlpurifier/locallib.php';
use HTMLPurifier;
use HTMLPurifier_Config;
use Mustache_LambdaHelper;

class mustache_sanitize_helper {

    private \HTMLPurifier $purifier;

    public function __construct() {
        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new \HTMLPurifier($config);
    }

    public function sanitize_html($context_key, \Mustache_LambdaHelper $helper) {
        $raw_html=$helper->getContextValue($context_key);
        $sanitized=$this->purifier->purify($raw_html);
        return $helper->render($sanitized);
    }
}
