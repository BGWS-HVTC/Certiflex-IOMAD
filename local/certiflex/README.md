Certiflex plugin
=======================================

Integration with Smarts API.

Sends completion data to the Smarts API.

During the installation plugin checks if "skillid" custom field for courses exists.
If not, creates a new fields category and that custom field.

Requirements
------------

- Username has to have 10 digits which should represent the dod_id on Smarts API.
- Course has to have skillid field filled in.
- Activity has to have one or more competencies associated and these competencies have to have idnumber provided. The idnumber should represent the skillid.

Log
---

New report "Certiflex log" has been added on admin report page (/admin/search.php#linkreports).

Scheduled task
--------------

local_certiflex\task\send_queue - sends completion data to the API.

Installation
------------

To install the plugin put the plugin folder (certiflex) in your Moodle folder under /local folder.

Configuration
-------------

Configure through Site administration > Plugins > Local plugins > Certiflex settings.