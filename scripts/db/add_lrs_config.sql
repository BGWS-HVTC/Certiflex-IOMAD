/*-- Create insert statement to add LRS configuration items.
-- columns - plugin, name, value
/**
logstore_xapi	*username	            e9f0a3c4-5215-45b6-812a-0772b271401e
logstore_xapi	*password	            00JmtJlIWAaQBIZ8kAqC
logstore_xapi	backgroundmode	        1
logstore_xapi	maxbatchsize	        30
logstore_xapi	resendfailedbatches	    1
logstore_xapi	mbox	                1
logstore_xapi	shortcourseid	        1
logstore_xapi	sendidnumber	        1
logstore_xapi	send_username	        1
logstore_xapi	send_jisc_data	        0
logstore_xapi	sendresponsechoices	    1
logstore_xapi	logguests	            0
logstore_xapi	routes	                \core\event\course_completed,\core\event\course_viewed,\core\event\user_created,\core\event\user_enrolment_created,\core\event\user_loggedin,\core\event\user_loggedout,\core\event\course_module_completion_updated,\mod_assign\event\assessable_submitted,\mod_assign\event\submission_graded,\mod_book\event\course_module_viewed,\mod_book\event\chapter_viewed,\mod_chat\event\course_module_viewed,\mod_choice\event\course_module_viewed,\mod_data\event\course_module_viewed,\mod_facetoface\event\course_module_viewed,\mod_feedback\event\course_module_viewed,\mod_feedback\event\response_submitted,\mod_folder\event\course_module_viewed,\mod_forum\event\course_module_viewed,\mod_forum\event\discussion_created,\mod_forum\event\discussion_viewed,\mod_forum\event\post_created,\mod_forum\event\user_report_viewed,\mod_glossary\event\course_module_viewed,\mod_imscp\event\course_module_viewed,\mod_lesson\event\course_module_viewed,\mod_lti\event\course_module_viewed,\mod_page\event\course_module_viewed,\mod_quiz\event\course_module_viewed,\mod_quiz\event\attempt_abandoned,\mod_quiz\event\attempt_started,\mod_quiz\event\attempt_reviewed,\mod_quiz\event\attempt_submitted,\mod_quiz\event\attempt_viewed,\mod_resource\event\course_module_viewed,\mod_scorm\event\course_module_viewed,\mod_scorm\event\sco_launched,\mod_scorm\event\scoreraw_submitted,\mod_scorm\event\status_submitted,\mod_survey\event\course_module_viewed,\mod_url\event\course_module_viewed,\mod_wiki\event\course_module_viewed,\mod_workshop\event\course_module_viewed,\totara_program\event\program_assigned
logstore_xapi	*endpoint	            https://engine.ips-lrs.com/RusticiEngine/lrs/54bb74e2388244c3abb27e629129a602/

 */
BEGIN
    IF EXISTS (SELECT FROM mdl_config_plugins WHERE plugin = 'logstore_xapi' name = 'version') THEN
        UPDATE mdl_config_plugins
        SET value = CASE WHEN name = 'username' THEN :LRS_USER
                         WHEN name = 'password' THEN :LRS_PASSWORD
                         WHEN name = 'backgroundmode' THEN '1'
                         WHEN name = 'maxbatchsize' THEN '30'
                         WHEN name = 'resendfailedbatches' THEN '1'
                         WHEN name = 'mbox' THEN '1'
                         WHEN name = 'shortcourseid' THEN '1'
                         WHEN name = 'sendidnumber' THEN '1'
                         WHEN name = 'send_username' THEN '1'
                         WHEN name = 'send_jisc_data' THEN '0'
                         WHEN name = 'sendresponsechoices' THEN '1'
                         WHEN name = 'logguests' THEN '0'
                         WHEN name = 'routes' THEN '\core\event\course_completed,\core\event\course_viewed,\core\event\user_created,\core\event\user_enrolment_created,\core\event\user_loggedin,\core\event\user_loggedout,\core\event\course_module_completion_updated,\mod_assign\event\assessable_submitted,\mod_assign\event\submission_graded,\mod_book\event\course_module_viewed,\mod_book\event\chapter_viewed,\mod_chat\event\course_module_viewed,\mod_choice\event\course_module_viewed,\mod_data\event\course_module_viewed,\mod_facetoface\event\course_module_viewed,\mod_feedback\event\course_module_viewed,\mod_feedback\event\response_submitted,\mod_folder\event\course_module_viewed,\mod_forum\event\course_module_viewed,\mod_forum\event\discussion_created,\mod_forum\event\discussion_viewed,\mod_forum\event\post_created,\mod_forum\event\user_report_viewed,\mod_glossary\event\course_module_viewed,\mod_imscp\event\course_module_viewed,\mod_lesson\event\course_module_viewed,\mod_lti\event\course_module_viewed,\mod_page\event\course_module_viewed,\mod_quiz\event\course_module_viewed,\mod_quiz\event\attempt_abandoned,\mod_quiz\event\attempt_started,\mod_quiz\event\attempt_reviewed,\mod_quiz\event\attempt_submitted,\mod_quiz\event\attempt_viewed,\mod_resource\event\course_module_viewed,\mod_scorm\event\course_module_viewed,\mod_scorm\event\sco_launched,\mod_scorm\event\scoreraw_submitted,\mod_scorm\event\status_submitted,\mod_survey\event\course_module_viewed,\mod_url\event\course_module_viewed,\mod_wiki\event\course_module_viewed,\mod_workshop\event\course_module_viewed,\totara_program\event\program_assigned'
                         WHEN name = 'endpoint' THEN :LRS_HOST
                         ELSE value
        END
        WHERE plugin = 'logstore_xapi'
    ELSE
        INSERT INTO mdl_config_plugins (plugin, name, value)
        VALUES ('logstore_xapi', 'username',            :LRS_USER),
               ('logstore_xapi', 'password',            :LRS_PASSWORD),
               ('logstore_xapi', 'backgroundmode',      '1'),
               ('logstore_xapi', 'maxbatchsize',        '30'),
               ('logstore_xapi', 'resendfailedbatches', '1'),
               ('logstore_xapi', 'mbox',                '1'),
               ('logstore_xapi', 'shortcourseid',       '1'),
               ('logstore_xapi', 'sendidnumber',        '1'),
               ('logstore_xapi', 'send_username',       '1'),
               ('logstore_xapi', 'send_jisc_data',      '0'),
               ('logstore_xapi', 'sendresponsechoices', '1'),
               ('logstore_xapi', 'logguests',           '0'),
               ('logstore_xapi', 'routes',              '\core\event\course_completed,\core\event\course_viewed,\core\event\user_created,\core\event\user_enrolment_created,\core\event\user_loggedin,\core\event\user_loggedout,\core\event\course_module_completion_updated,\mod_assign\event\assessable_submitted,\mod_assign\event\submission_graded,\mod_book\event\course_module_viewed,\mod_book\event\chapter_viewed,\mod_chat\event\course_module_viewed,\mod_choice\event\course_module_viewed,\mod_data\event\course_module_viewed,\mod_facetoface\event\course_module_viewed,\mod_feedback\event\course_module_viewed,\mod_feedback\event\response_submitted,\mod_folder\event\course_module_viewed,\mod_forum\event\course_module_viewed,\mod_forum\event\discussion_created,\mod_forum\event\discussion_viewed,\mod_forum\event\post_created,\mod_forum\event\user_report_viewed,\mod_glossary\event\course_module_viewed,\mod_imscp\event\course_module_viewed,\mod_lesson\event\course_module_viewed,\mod_lti\event\course_module_viewed,\mod_page\event\course_module_viewed,\mod_quiz\event\course_module_viewed,\mod_quiz\event\attempt_abandoned,\mod_quiz\event\attempt_started,\mod_quiz\event\attempt_reviewed,\mod_quiz\event\attempt_submitted,\mod_quiz\event\attempt_viewed,\mod_resource\event\course_module_viewed,\mod_scorm\event\course_module_viewed,\mod_scorm\event\sco_launched,\mod_scorm\event\scoreraw_submitted,\mod_scorm\event\status_submitted,\mod_survey\event\course_module_viewed,\mod_url\event\course_module_viewed,\mod_wiki\event\course_module_viewed,\mod_workshop\event\course_module_viewed,\totara_program\event\program_assigned'),
               ('logstore_xapi', 'endpoint',            :LRS_HOST);
    END IF
END*/

UPDATE mdl_config_plugins
        SET value = CASE WHEN name = 'username' THEN 'e9f0a3c4-5215-45b6-812a-0772b271401e'
                         WHEN name = 'password' THEN '00JmtJlIWAaQBIZ8kAqC'
                         WHEN name = 'backgroundmode' THEN '1'
                         WHEN name = 'maxbatchsize' THEN '30'
                         WHEN name = 'resendfailedbatches' THEN '1'
                         WHEN name = 'mbox' THEN '1'
                         WHEN name = 'shortcourseid' THEN '1'
                         WHEN name = 'sendidnumber' THEN '1'
                         WHEN name = 'send_username' THEN '1'
                         WHEN name = 'send_jisc_data' THEN '0'
                         WHEN name = 'sendresponsechoices' THEN '1'
                         WHEN name = 'logguests' THEN '0'
                         WHEN name = 'routes' THEN '\core\event\course_completed,\core\event\course_viewed,\core\event\user_created,\core\event\user_enrolment_created,\core\event\user_loggedin,\core\event\user_loggedout,\core\event\course_module_completion_updated,\mod_assign\event\assessable_submitted,\mod_assign\event\submission_graded,\mod_book\event\course_module_viewed,\mod_book\event\chapter_viewed,\mod_chat\event\course_module_viewed,\mod_choice\event\course_module_viewed,\mod_data\event\course_module_viewed,\mod_facetoface\event\course_module_viewed,\mod_feedback\event\course_module_viewed,\mod_feedback\event\response_submitted,\mod_folder\event\course_module_viewed,\mod_forum\event\course_module_viewed,\mod_forum\event\discussion_created,\mod_forum\event\discussion_viewed,\mod_forum\event\post_created,\mod_forum\event\user_report_viewed,\mod_glossary\event\course_module_viewed,\mod_imscp\event\course_module_viewed,\mod_lesson\event\course_module_viewed,\mod_lti\event\course_module_viewed,\mod_page\event\course_module_viewed,\mod_quiz\event\course_module_viewed,\mod_quiz\event\attempt_abandoned,\mod_quiz\event\attempt_started,\mod_quiz\event\attempt_reviewed,\mod_quiz\event\attempt_submitted,\mod_quiz\event\attempt_viewed,\mod_resource\event\course_module_viewed,\mod_scorm\event\course_module_viewed,\mod_scorm\event\sco_launched,\mod_scorm\event\scoreraw_submitted,\mod_scorm\event\status_submitted,\mod_survey\event\course_module_viewed,\mod_url\event\course_module_viewed,\mod_wiki\event\course_module_viewed,\mod_workshop\event\course_module_viewed,\totara_program\event\program_assigned'
                         WHEN name = 'endpoint' THEN 'https://engine.ips-lrs.com/RusticiEngine/lrs/54bb74e2388244c3abb27e629129a602'
                         ELSE value
        END
        WHERE plugin = 'logstore_xapi'