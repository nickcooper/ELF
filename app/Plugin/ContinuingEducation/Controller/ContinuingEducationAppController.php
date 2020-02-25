<?php
/**
 * Continuing Education App Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class ContinuingEducationAppController extends AppController
{
    /**
     * beforeFilter callback
     *
     * @return void
     * @access  public
     */
    public function beforeFilter ()
    {
        parent::beforeFilter();

        if (Configure::read('Configuration.continuing_ed_type') == 'minimal' && $this->name != 'CourseRosters' && $this->action != 'upload')
        {
            $this->redirect(array('plugin' => 'continuing_education', 'controller' => 'course_rosters', 'action' => 'upload'));
        }
    }
}