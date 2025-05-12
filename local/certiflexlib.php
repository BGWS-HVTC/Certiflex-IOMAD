<?php
global $CFG;
require_once($CFG->dirroot . '/local/mustache_sanitize_helper.php');

defined('MOODLE_INTERNAL') || die();

function add_mustache_sanitize_helper($mustache) {
    if ($mustache->hasHelper('sanitize_html')) {
        // Grab a copy of the existing helper to be restored later.
        $sanitize_html_helper = $mustache->getHelper('sanitize_html');
    } else {
        // Helper doesn't exist.
        $sanitize_html_helper = null;
    }
    $mustache->addHelper('sanitize_html', new mustache_sanitize_helper());
}