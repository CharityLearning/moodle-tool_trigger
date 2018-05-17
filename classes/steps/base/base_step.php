<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Base step class.
 *
 * @package    tool_trigger
 * @copyright  Matt Porritt <mattp@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_trigger\steps\base;

defined('MOODLE_INTERNAL') || die;

/**
 * Base step class.
 *
 * @package    tool_trigger
 * @copyright  Matt Porritt <mattp@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base_step {

    protected $data = [];

    public function __construct($jsondata = null) {
        if ($jsondata) {
            $this->data = json_decode($jsondata, true);
            $this->init();
        }
    }

    /**
     * Set up instance variables based on jsondata.
     */
    protected function init() {
    }

    /**
     * Returns the step name.
     *
     * @return string human readable step name.
     */
    abstract static public function get_step_name();

    /**
     * Returns the step description.
     *
     * @return string human readable step description.
     */
    abstract static public function get_step_desc();

    /**
     * @param \stdClass $step The `tool_trigger_steps` record for this step instance
     * @param \stdClass $trigger The `tool_trigger_queue` record for this execution
     * of the workflow.
     * @param \core\event\base $event (Read-only) The deserialized event object that triggered this execution
     * @param array $stepresults (Read-Write) Data aggregated from the return values of previous steps in
     * the workflow.
     * @return array<bool, array> Returns an array. The first element is a boolean
     * indicating whether or not the step was executed successfully; the second element should
     * be the $previousstepresult object, optionally mutated to provide data to
     * later steps.
     */
    abstract public function execute($step, $trigger, $event, $stepresults);

    /**
     * Instantiate a form for this step.
     *
     * If all you need to do is add fields to the form, then you should be able to get by
     * with this default implementation, and override the "form_definition()" method to your
     * step's class.
     *
     * If you want more control over other parts of the form, then override this method
     * to return a custom subclass of \base_form instead.
     *
     * @param mixed $customdata
     * @param mixed $ajaxformdata
     * @return \moodleform
     */
    public function make_form($customdata, $ajaxformdata) {
        return new base_form(null, $customdata, 'post', '', null, true, $ajaxformdata, $this);
    }

    /**
     * A callback to add fields to the step definition form, specific to each step class.
     *
     * @param \moodleform $form
     * @param \MoodleQuickForm $mform
     * @param mixed $customdata
     */
    abstract public function form_definition_extra($form, $mform, $customdata);
}