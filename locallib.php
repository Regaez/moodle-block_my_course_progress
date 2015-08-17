<?php
/**
 * Local Library file
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Return sorted list of user courses
 *
 * @param bool $showallcourses if set true all courses will be visible.
 * @return array list of sorted courses and count of courses.
 */
function block_my_course_progress_get_sorted_courses() {
    global $USER;

    $courses = enrol_get_my_course_progress();
    $site = get_site();

    if (array_key_exists($site->id,$courses)) {
        unset($courses[$site->id]);
    }

    foreach ($courses as $c) {
        if (isset($USER->lastcourseaccess[$c->id])) {
            $courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
        } else {
            $courses[$c->id]->lastaccess = 0;
        }
    }

    // Get remote courses.
    $remotecourses = array();
    if (is_enabled_auth('mnet')) {
        $remotecourses = get_my_remotecourses();
    }
    // Remote courses will have -ve remoteid as key, so it can be differentiated from normal courses
    foreach ($remotecourses as $id => $val) {
        $remoteid = $val->remoteid * -1;
        $val->id = $remoteid;
        $courses[$remoteid] = $val;
    }

    // From list extract site courses for overview
    $sitecourses = array();
    foreach ($courses as $key => $course) {
        if ($course->id > 0) {
            $sitecourses[$key] = $course;
        }
    }
    return array($courses, $sitecourses, count($courses));
}
