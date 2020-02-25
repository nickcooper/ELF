<?php
/**
 * CourseRosters Controller
 *
 * @package ContinuingEducation.Controller
 * @author  Iowa Interactive, LLC.
 */
class CourseRostersController extends ContinuingEducationAppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseRosters';

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('RequestHandler');

    /**
     * Add student to roster for course section
     *
     * @param int $course_section_id course section record id
     *
     * @return void
     * @access public
     *
     * @todo Remove accounts drop down and use user search element
     * @todo Add a line for each allowed test attempt to course performance section
     */
    public function add($course_section_id)
    {
        $this->CourseRoster->CourseSection->id = $course_section_id;

        if (!$this->CourseRoster->CourseSection->exists())
        {
            throw new NotFoundException(__('Invalid course section'));
        }

        // do we have a searchable id?
        $searchable_id = null;

        if (isset($this->params['named']['searchable']))
        {
            $searchable_id = $this->params['named']['searchable'];
        }
        elseif ($this->request->is('post') && isset($this->request->data['Searchable']))
        {
            $searchable_id = $this->request->data['Searchable'];
        }

        // check for Searchable id
        if ($searchable_id)
        {
            // check to see if this student has already been added to the roster
            if ($student = $this->CourseRoster->getStudent($searchable_id, $course_section_id))
            {
                // student already exists
                $this->Session->setFlash(__('The selected student has already been added'));
                $this->CourseRoster->read(null, $student['CourseRoster']['id']);
            }
            else
            {
                // format the data
                $this->CourseRoster->create();
                $this->CourseRoster->set('account_id', $searchable_id);
                $this->CourseRoster->set('course_section_id', $course_section_id);

                // add the account id to the managers table
                if ($this->CourseRoster->save())
                {
                    // success
                    $this->Session->setFlash(__('The new student has been added'));

                    // get the associated course section information
                    $course_section = $this->CourseRoster->CourseSection->findById($course_section_id);

                    // build the message for the system note
                    $msg = sprintf(
                        'Your account has been added to Course "%s", Section "%s", by "%s" on %s.',
                        $course_section['CourseSection']['label'],
                        $course_section['CourseSection']['course_section_number'],
                        CakeSession::read("Auth.User.label"),
                        date('Y-m-d')
                    );

                    // write the system note
                    $this->CourseRoster->addCourseRosterSysNote(
                        $searchable_id,
                        $msg
                    );
                }
                else
                {
                    // fail
                    $this->Session->setFlash(__('Failed to add student'));
                }
            }

            // redirect
            $this->redirect(
                array(
                    'plugin'     => 'continuing_education',
                    'controller' => 'course_rosters',
                    'action'     => 'edit',
                    $this->CourseRoster->id,
                    'return'     => $this->params['named']['return'],
                ),
                null,
                true,
                'forward'
            );
        }

        // no Searchable id, let's go get one
        $this->redirect(
            array(
                'plugin'     => 'searchable',
                'controller' => 'searchable',
                'action'     => 'locator',
                'fp'         => 'Accounts',
                'fo'         => 'Account',
                'return'     => base64_encode($this->here),
            ),
            null,
            false,
            'skip'
        );
    }

    /**
     * Edit student info on roster
     *
     * @param int $id course roster id
     *
     * @return void
     */
    public function edit($id)
    {
        try
        {
            if ($this->request->is('post') || $this->request->is('put'))
            {
                // Loop through the exam scores and unset anything that's completely empty
                if (!empty($this->request->data['ExamScore']))
                {
                    foreach ($this->request->data['ExamScore'] as $i => $exam_score)
                    {
                        if (!GenLib::array_filter_recursive($exam_score))
                        {
                            unset($this->request->data['ExamScore'][$i]);
                        }
                    }
                }

                if ($this->CourseRoster->add($this->request->data))
                {
                    $this->Session->setFlash(__('Student information updated.'));

                    // If 'Save & Complete', carry on to complete.
                    if (isset($this->request->data['complete']))
                    {
                        $this->complete($this->request->data['CourseRoster']['id']);
                    }
                }
                else
                {
                    $this->Session->setFlash(__('Student data could not be saved. Please, try again.'));
                }
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->data = $this->CourseRoster->details($id);

        $this->set('editable', empty($this->data['CourseRoster']['completed']));
        $this->set('completion_cert_link', '');
        $this->set('test_attempts', $this->CourseRoster->getTestAttempts($id));
    }

    /**
     * Complete course for a student
     *
     * @param int $id course roster id
     *
     * @return void
     */
    public function complete($id = null)
    {
        try
        {
            // write a system note if the course complete is successful
            if ($this->CourseRoster->complete($id))
            {
                // set the flash message
                $this->Session->setFlash(__('Course marked completed'));

                // get the course roster information
                $course_roster = $this->CourseRoster->findById($id);

                // get the associated course section information
                $course_section = $this->CourseRoster->CourseSection->findById($course_roster['CourseRoster']['course_section_id']);

                // get the associated account information
                $account = $this->CourseRoster->Account->findById($course_roster['CourseRoster']['account_id']);

                // build the message for the system note
                $msg = sprintf(
                    'Course "%s", Section "%s", has been saved and completed by "%s" on %s.',
                    $course_section['CourseSection']['label'],
                    $course_section['CourseSection']['course_section_number'],
                    CakeSession::read("Auth.User.label"),
                    date('Y-m-d')
                );

                // write the system note
                $this->CourseRoster->addCourseRosterSysNote(
                    $course_roster['CourseRoster']['account_id'],
                    $msg
                );
            }
            else
            {
                $this->Session->setFlash(__('There was an error updating the student record'));

            }
        }
        catch(Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(array('action' => 'edit', $id));
    }

    /**
     * Remove student from roster for course section
     *
     * @param int $id course roster record id
     *
     * @return void
     * @access public
     */
    public function delete($id = null)
    {
        $this->CourseRoster->id = $id;

        if (!$this->CourseRoster->exists())
        {
            throw new NotFoundException(__('Invalid Student'));
        }

        $course_section_id = $this->CourseRoster->getCourseSectionForStudent($id);

        // get the course roster information
        $course_roster = $this->CourseRoster->findById($id);

        if ($this->CourseRoster->delete($id))
        {
            $this->Session->setFlash(__('Student deleted'));

            // get the associated course section information
            $course_section = $this->CourseRoster->CourseSection->findById($course_section_id);

            // build the message for the system note
            $msg = sprintf(
                'Your account has been deleted from Course "%s", Section "%s", by "%s" on %s.',
                $course_section['CourseSection']['label'],
                $course_section['CourseSection']['course_section_number'],
                CakeSession::read("Auth.User.label"),
                date('Y-m-d')
            );

            // write the system note
            $this->CourseRoster->addCourseRosterSysNote(
                $course_roster['CourseRoster']['account_id'],
                $msg
            );
        }
        else
        {
            $this->Session->setFlash(__('There was an error deleting the student'));
        }

        // redirect back to course section view
        $this->redirect(
            array(
                'controller' => 'course_sections',
                'action'     => 'view',
                $course_section_id,
            )
        );
    }

    /**
     * Export roster for a course number
     *
     * @param str $course_section_number course section number (not primary record id)
     *
     * @return void
     * @access public
     */
    public function export($course_section_number)
    {
        $course_section_id = $this->CourseRoster->CourseSection->field(
            'id',
            array(
                'course_section_number' => $course_section_number,
            )
        );

        $roster = $this->CourseRoster->CourseSection->details($course_section_id);

        $header = array(
            'Training Provider Name',
            'Training Instructor',
            'Course Section Number',
            'Course Location',
            'Course Start Date',
            'Course End Date',
            'Name',
            'Student Number',
            'Complete',
        );

        for ($i = 0; $i < $roster['CourseCatalog']['test_attempts']; $i++)
        {
            $header[] = "Test Date " . ($i + 1);
            $header[] = "Test Result " . ($i + 1);
            $header[] = "Test Score " . ($i + 1);
        }

        $result = array($header);
        $row_to_add = array();

        foreach ($roster['CourseRoster'] as $row)
        {
            $row_to_add = array(
                $roster['TrainingProvider']['label'],
                $roster['Account']['label'],
                $roster['CourseSection']['course_section_number'],
                $roster['Address']['label'],
                $roster['CourseSection']['start_date'],
                $roster['CourseSection']['end_date'],
                $row['Account']['label'],
                $row['student_number'],
                $row['completed'] ? 'Yes' : 'No',
            );

            foreach ($row['ExamScore'] as $score)
            {
                $row_to_add[] = $score['exam_date'];
                $row_to_add[] = $score['pass'] ? 'Passed' : 'Failed';
                $row_to_add[] = $score['score'];
            }

            $result[] = $row_to_add;
        }

        $this->set('data', $result);

        // turn debug off
        Configure::write('debug', 0);
    }

    /**
     * Upload course data
     *
     * @return void
     * @access public
     */
    public function upload()
    {
        // process form post
        if ($this->request->is('post'))
        {
            try
            {
                // Check to see if upload data exists
                if (empty($this->request->data['CourseRoster']['upload']['type']))
                {
                    throw new Exception('Course file missing.');
                }

                // Check to see if file format is csv
                if ($this->request->data['CourseRoster']['upload']['type'] != 'text/csv')
                {
                    if ($this->request->data['CourseRoster']['upload']['type'] != 'application/vnd.ms-excel')
                    {
                        throw new Exception('Uploaded file type not CSV format.');
                    }
                }

                // Set the tmp roster directory .../app/tmp/rosters
                $tmp_roster_dir = TMP.'rosters';

                // Create the tmp roster directory if needed
                if (!file_exists($tmp_roster_dir))
                {
                    if (!mkdir($tmp_roster_dir, 0755, true))
                    {
                        throw new Exception('Temp directory could not be created');
                    }
                }

                // Define locations of uploaded, timestamp, and roster files
                $uploaded_file = $this->request->data['CourseRoster']['upload']['tmp_name'];
                $timestamp_file = $tmp_roster_dir.DS.date('YmdHis').'.csv';
                $roster_file = $tmp_roster_dir.DS.'roster.csv';

                if (!copy($uploaded_file, $timestamp_file))
                {
                    throw new Exception('Could not copy uploaded file.');
                }

                // Validate the data
                $this->CourseRoster->validateUpload($timestamp_file);

                // create the roster.csv file for background processing later
                if (!copy($timestamp_file, $roster_file))
                {
                    throw new Exception('Could not copy timestamp file.');
                }

                $this->Session->setFlash('Course file upload successful!');

            }
            catch (Exception $e)
            {
                $this->Session->setFlash($e->getMessage());
            }
        }
    }

    /**
     * Edit roster expire_date
     *
     * @param int $id course roster id
     *
     * @return void
     */
    public function edit_expire_date($id)
    {
        $roster = $this->CourseRoster->find(
            'first',
            array(
                'contain' => array(
                    'Account',
                    'CourseSection' => array(
                        'CourseCatalog' => array(
                            'CourseCatalogLicenseType'
                        )
                    )
                ),
                'conditions' => array(
                    'CourseRoster.id' => $id
                )
            )
        );
        $this->set('roster', $roster);

        if (empty($this->request->data))
        {
            $this->request->data = $roster;
        }
        else
        {
            if ($roster['CourseRoster']['expire_date'] != GenLib::dateFormat($this->request->data['CourseRoster']['expire_date'], 'Y-m-d'))
            {

                $this->CourseRoster->id = $id;
                if (!$this->CourseRoster->saveField('expire_date', $this->request->data['CourseRoster']['expire_date']))
                {
                    throw new Exception('Could not save course roster expire date.');
                }

                $this->Session->setFlash('Course roster expire date updated');

                $license_types_related_to_course_roster = Hash::extract($roster, 'CourseSection.CourseCatalog.CourseCatalogLicenseType.{n}.license_type_id');

                $licenses = $this->CourseRoster->Account->License->find(
                    'all',
                    array(
                        'conditions' => array(
                            'License.foreign_obj' => 'Account',
                            'License.foreign_key' => $roster['Account']['id'],
                            'License.license_type_id' => $license_types_related_to_course_roster
                        )
                    )
                );

                if (count($licenses) > 0)
                {
                    foreach ($licenses as $license)
                    {
                        // dispatch the editExpireDate event for listeners
                        $this->CourseRoster->Account->License->dispatch('Model-CourseRoster-editExpireDateEachLicense', array('license_id' => $license['License']['id']));
                    }
                }
            }
            else
            {
                // new date is not different then current date
                $this->Session->setFlash('Course roster expire date was not updated. The submitted date was not different.');
            }
        }
    }
}