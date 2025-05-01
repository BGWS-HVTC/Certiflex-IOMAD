<?php
/**
 * Script to delete scheduled reports
 */

define("CLI_SCRIPT", true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/totara/core/lib.php');
require_once($CFG->dirroot . '/totara/reportbuilder/lib.php');

// File should contain one report schedule id per line
$reports_to_delete = file('scheduled_reports_to_delete.txt', FILE_IGNORE_NEW_LINES);

foreach ($reports_to_delete as $reportid) {

    mtrace("Deleting schedule report $reportid");

    if (!$scheduledreport = $DB->get_record('report_builder_schedule', array('id' => $reportid))) {
        mtrace("Could not find report $reportid");
        continue;
    }

    $reportname = $DB->get_field('report_builder', 'fullname', array('id' => $scheduledreport->reportid));

    $select = "scheduleid = ?";

    $DB->delete_records_select('report_builder_schedule_email_audience', $select, array($reportid));
    $DB->delete_records_select('report_builder_schedule_email_systemuser', $select, array($reportid));
    $DB->delete_records_select('report_builder_schedule_email_external', $select, array($reportid));
    $DB->delete_records('report_builder_schedule', array('id' => $reportid));
    \totara_reportbuilder\event\scheduled_report_deleted::create_from_schedule($scheduledreport)->trigger();

    mtrace("Deleted schedule report $reportid");
}
