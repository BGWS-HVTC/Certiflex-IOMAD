@local @local_certiflex
Feature: Add log
  As a admin
  I need to add log

  Background:
    Given the following "users" exist:
      | username   | firstname | lastname | email                |
      | 1234567890 | Sam1      | Student1 | student1@example.com |
    And the following lp "frameworks" exist:
      | shortname | idnumber |
      | Test-Framework | ID-FW1 |
    And the following lp "competencies" exist:
      | shortname | framework |
      | Test-Comp1 | ID-FW1 |
      | Test-Comp2 | ID-FW1 |
    And the following "courses" exist:
      | fullname | shortname | category | enablecompletion | skill_id |
      | Course 1 | C1 | 0 | 1 | 12345 |
    And I enable "selfcompletion" "block" plugin
    And the following "blocks" exist:
      | blockname        | contextlevel | reference | pagetypepattern | defaultregion |
      | selfcompletion   | Course       | C1        | course-view-*   | side-pre      |
    And the following "course enrolments" exist:
      | user        | course    | role      |
      | 1234567890  | C1        | student   |
    And the following "activity" exists:
      | activity                                      | page                    |
      | course                                        | C1                      |
      | name                                          | Page 1                  |
      | completion                                    | 1                       |
      | completionview                                | 1                       |
      
  Scenario: Use course reset to clear all attempt data
    When I am on the "C1" "Course" page logged in as "admin"
    And I navigate to "Course completion" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | id_criteria_self | 1 |
    And I press "Save changes"

    When I am on the "C1" course page logged in as 1234567890
    And I follow "Complete course"
    And I should see "Confirm self completion"
    And I press "Yes"

    And I log out