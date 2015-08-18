<?php
/**
 * My Course Progress Block
 *
 * @package     block
 * @subpackage  block_my_course_progress
 * @author      Thomas Threadgold <tj.threadgold@gmail.com>
 * @copyright   2015 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/blocks/my_course_progress/locallib.php');

class block_my_course_progress extends block_base {

    /**
     * Block initialization
     */
    public function init() {
        $this->title   = get_string('block_title', 'block_my_course_progress');
    }

    /**
     * Return contents of my_course_progress block
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        global $CFG, $DB;

        if($this->content !== NULL) {
            return $this->content;
        }

        $config = get_config('block_my_course_progress');

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        $content = array();
        $sortedcourses = block_my_course_progress_get_sorted_courses();

        $renderer = $this->page->get_renderer('block_my_course_progress');

        if (empty($sortedcourses)) {
            $this->content->text .= get_string('nocourses','my');
        } else {
            $this->content->text = $renderer->course_grid($sortedcourses);
        }

        return $this->content;
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Sets block header to be hidden or visible
     *
     * @return bool if true then header will be visible.
     */
    public function hide_header() {
        return false;
    }

    /**
     * Sets block default location to center of page
     *
     * @return bool if true then header will be visible.
     */
    public function specialization() {
        global $DB;
        $this->instance->defaultregion = 'content';
        $this->instance->defaultweight = 0;
        $DB->update_record('block_instances', $this->instance);
    }
}