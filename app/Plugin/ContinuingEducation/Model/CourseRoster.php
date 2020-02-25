<?php
/**
 * CourseRoster Model
 *
 * @package ContinuingEducation.Model
 * @author  Iowa Interactive, LLC.
 */
class CourseRoster extends ContinuingEducationAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'CourseRoster';

    /**
     * Model Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'OutputDocuments.OutputDocument',
        'Uploads.Upload' => array(
            'AccountPhoto' => array(
                'save_location' => 'files',
                'allowed_types' => array('image/jpeg'),
                'association' => 'hasOne',
            ),
        ),
    );

     /**
     * hasMany Associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'ExamScore' => array(
            'className' => 'ContinuingEducation.ExamScore',
            'foreignKey' => 'foreign_key',
            'conditions' => array('ExamScore.foreign_obj' => 'CourseRoster'),
            'order' => array('ExamScore.exam_date' => 'DESC'),
        ),
        'AccountPhoto' => array(
            'className' => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'AccountPhoto.foreign_obj' => 'CourseRoster',
                'AccountPhoto.identifier' => 'AccountPhoto'
            )
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'CourseSection' => array(
            'className' => 'ContinuingEducation.CourseSection',
            'foreignKey' => 'course_section_id'
        ),
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        )
    );

    /**
     * beforeSave callback
     *
     * @param string $options options array
     *
     * @access public
     *
     * @return boolean
     */
    public function beforeSave($options = array())
    {
        // if there's an account id present set the student number
        if (!empty($this->data[$this->name]['account_id']))
        {
            $course_section_number = $this->CourseSection->field(
                'course_section_number',
                array('CourseSection.id' => $this->data[$this->name]['course_section_id'])
            );

            $student_number = sprintf(
                '%s-%s',
                $course_section_number,
                sprintf("%06d", $this->data[$this->name]['account_id'])
            );

            // set the student number
            $this->data[$this->alias]['student_number'] = $student_number;
        }

        return parent::beforeSave($options);
    }

    /**
     * Get course roster for a given course section id
     *
     * @param int $course_section_id id of the course section you want the roster for
     *
     * @access public
     *
     * @return array
     */
    public function getRosterForCourseSection($course_section_id)
    {
        return $this->CourseSection->details($course_section_id);
    }

    /**
     * Get a course section id for a given student provided the course roster id
     *
     * @param int $id if from the course roster for the student
     *
     * @access public
     *
     * @return int
     */
    public function getCourseSectionForStudent($id)
    {
        return $this->field(
            'course_section_id', array(
                'CourseRoster.id' => $id
            )
        );
    }

    /**
     * Get all current courses for a student by account id
     *
     * @param int $account_id Id of account for the student
     *
     * @access public
     *
     * @return array
     */
    public function getCurrentCoursesForStudent($account_id)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'CourseRoster.account_id' => $account_id,
                ),
                'contain' => array(
                    'Account',
                    'CourseSection' => array(
                        'CourseCatalog',
                        'TrainingProvider',
                    ),
                )
            )
        );
    }

    // Replacing AppController functions

    /**
     * Course Roster Object Details
     *
     * @param int    $id      id of the course roster item you want details for
     * @param string $contain contain array
     *
     * @access public
     *
     * @return array
     */
    public function details($id, $contain = false)
    {
        if (!$contain)
        {
            $contain = array(
                'Account',
                'AccountPhoto',
                'CourseSection' => array(
                    'CourseCatalog',
                    'TrainingProvider',
                    'Address',
                ),
                'ExamScore',
            );
        }

        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'CourseRoster.id' => $id
                ),
                'contain' => $contain
             )
        );
    }

    /**
     * Returns Account::getList containing a list of accouts for students in a course section
     *
     * @param int    $course_section_id get a course roster listing for a specific course section
     * @param string $options           options array
     *
     * @access public
     *
     * @return array
     */
    public function getList($course_section_id = null, $options = null)
    {
        $this->loadModel('Accounts.Account');

        if (!is_int($course_section_id))
        {
            throw new Exception("Invalid course section");
        }

        $user_ids = $this->find(
            'list', array(
                'conditions' => array("{$this->name}.course_section_id" => $course_section_id),
                 'fields'     => array("{$this->name}.id", "{$this->name}.account_id"),
            )
        );

        return $this->Account->getList(array('Account.id' => $user_ids));
    }

    /**
     * Returns an individual student from a course given an account id and course sections
     *
     * @param int $account_id        Account record id
     * @param int $course_section_id Course section record id
     *
     * @access public
     *
     * @return array|boolean
     */
    public function getStudent($account_id, $course_section_id)
    {
        return $this->find(
            'first', array(
                'conditions' => array(
                    'CourseRoster.account_id'        => $account_id,
                    'CourseRoster.course_section_id' => $course_section_id,
                )
            )
        );
    }

    /**
     * Returns a course section id for a roster item
     *
     * @param int $id course roster id
     *
     * @access public
     *
     * @return int
     */
    public function getCourseSectionId($id)
    {
        $this->id = $id;
        return $this->field('course_section_id');
    }

    /**
     * Returns the number of test attempts for the course a student is taking
     *
     * @param int $id CourseRoster id
     *
     * @access public
     *
     * @return int
     */
    public function getTestAttempts($id)
    {
        $roster = $this->find(
            'first', array(
                'conditions' => array(
                    "{$this->alias}.id" => $id
                ),
                'contain' => array(
                    'CourseSection' => array(
                        'CourseCatalog',
                    ),
                ),
            )
        );

        return($roster['CourseSection']['CourseCatalog']['test_attempts']);
    }

    /**
     * Overriding AppModel::delete to remove related ExamScore objects first
     *
     * @param int  $id      CourseRoster id
     * @param bool $cascade true/false cascade
     *
     * @access public
     *
     * @return boolean
     */
    public function delete($id = null, $cascade = true)
    {
        // get the details before delete
        $roster = $this->details($id);

        // first delete related exam scores
        if ($this->ExamScore->deleteExamScoresForStudent($id))
        {
            if (parent::delete($id))
            {
                // get a list of affected licenses
                $LicenseModel = ClassRegistry::init('Licenses.License');
                $license_ids = $LicenseModel->find(
                    'list',
                    array(
                        'conditions' => array('License.foreign_obj' => 'Account', 'License.foreign_key' => $roster['Account']['id']),
                        'fields' => array('id')
                    )
                );

                // dispatch the course complete event for listeners
                $this->dispatch(
                    'Model-CourseRoster-delete',
                    array(
                        'license_id' => $license_ids
                    )
                );

                return true;
            }
        }

        return false;
    }

    /**
     * Complete course for a student
     *
     * @param int $id course roster id
     *
     * @access public
     *
     * @return boolean
     */
    public function complete($id)
    {
        try
        {
            if (!$this->exists($id))
            {
                throw new Exception(__('Invalid student'));
            }

            $roster = $this->details(
                $id,
                array(
                    'Account',
                    'AccountPhoto',
                    'CourseSection' => array(
                        'CourseCatalog' => array(
                            'CourseCatalogLicenseType',
                        ),
                        'TrainingProvider',
                        'Address',
                    ),
                    'ExamScore',
                )
            );

            // does this course require a passing exam
            if ($roster['CourseSection']['CourseCatalog']['test_attempts'] > 0)
            {
                // default passed exams count
                $passed_count = 0;

                foreach ($roster['ExamScore'] as $score)
                {
                    if (!empty($score['pass']))
                    {
                        $passed_count++;
                    }
                }

                // has the student passed the exam?
                if ($passed_count === 0)
                {
                    throw new Exception(__('Student is missing a passing exam score.'));
                }
            }

            // attempt to complete the course roster
            $this->id = $id;

            if (!$this->saveField('completed', true))
            {
                throw new Exception('Failed to complete the course roster.');
            }

            // get a list of affected licenses
            $LicenseModel = ClassRegistry::init('Licenses.License');
            $license_ids = $LicenseModel->find(
                'list',
                array(
                    'conditions' => array('License.foreign_obj' => 'Account', 'License.foreign_key' => $roster['Account']['id']),
                    'fields' => array('id')
                )
            );

            // dispatch the course complete event for listeners
            $this->dispatch(
                'Model-CourseRoster-complete',
                array(
                    'course_roster_id' => $id,
                    'account_id' => $roster['Account']['id'],
                    'license_id' => $license_ids,
                )
            );

            return true;
        }
        catch (Exception $e)
        {
            // if you got here the record failed to update
            throw $e;
        }
    }

    /**
     * courseRosterExpirations
     *
     * @param array $event event array
     *
     * @access public
     *
     * @return void
     */
    public function courseRosterExpirations ($event = array())
    {
        // calc the new course expire date
        $expire_date = $this->calcExpireDate($event->data['course_roster_id']);

        // update the course roster expire date
        $this->id = $event->data['course_roster_id'];
        $this->saveField('expire_date', $expire_date);
    }

    /**
     * CalcExpireDate
     *
     * @param int $roster_id The roster id for the student
     * @param int $cycle     Flag specifying whether to use the renewal cycle value from the database
     *
     * @access public
     *
     * @return void
     */
    public function calcExpireDate($roster_id = null, $cycle = true)
    {
        try
        {
            // get the roster details
            $roster = $this->details($roster_id);

            if (!$roster)
            {
                throw new Exception('Missing Roster data.');
            }

            // set latest_end_date to the end date of the current course being completed
            $latest_end_date = $roster['CourseSection']['end_date'];

            // find the historical (previous) courses for the specified account that are in the same
            // course group (bucket) as the course being completed
            $historical_courses = $this->find(
                'all',
                array(
                    'conditions' => array(
                        'CourseRoster.account_id' => $roster['CourseRoster']['account_id'],
                    ),
                    'contain' => array(
                        'CourseSection' => array('CourseCatalog'),
                    )
                )
            );

            // sort the courses based on course end date
            $historical_sorted_courses = Hash::sort($historical_courses, '{n}.CourseSection.end_date', 'desc');

            // of the courses that apply, if the end date comes after the previous_end_date,
            // reset the previous_end_date
            foreach ($historical_sorted_courses as $historical_sorted)
            {
                // make sure the course in the student's course history is in the same bucket as the course being completed
                if ($historical_sorted['CourseSection']['CourseCatalog']['course_group_id'] == $roster['CourseSection']['CourseCatalog']['course_group_id'])
                {
                    // the course being completed has already been added to the student's course history,
                    // so don't compare it to itself
                    if ($historical_sorted['CourseSection']['course_section_number'] != $roster['CourseSection']['course_section_number'])
                    {
                        // if the end date of the currently completed course falls between the end date of the previous course
                        // and the previous course's expiration date, set the latest end date to the previous course's
                        // expiration date
                        if (($latest_end_date > $historical_sorted['CourseSection']['end_date']) && ($latest_end_date < $historical_sorted['CourseRoster']['expire_date']))
                        {
                            $latest_end_date = $historical_sorted['CourseRoster']['expire_date'];
                        }
                    }
                }
            }

            // get the course cycle period
            if ($cycle === true)
            {
                $cycle = $roster['CourseSection']['CourseCatalog']['cycle'];
            }
            else
            {
                $cycle = 0;
            }

            // return the latest end date + the cycle period
            return date('Y-m-d', strtotime(sprintf('%s + %s months', $latest_end_date, $cycle)));
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Validate uploaded course roster data
     *
     * @param string $uploaded_file uploaded file path
     *
     * @access public
     *
     * @todo turn return values into return flags
     * @todo add switch case to interpret return flags
     *
     * @return void
     */
    public function validateUpload($uploaded_file = null)
    {
        // check that passed in file exists
        if (file_exists($uploaded_file))
        {
            // Open file
            if (($fh = fopen($uploaded_file, "r")) !== false)
            {
                // set an array to hold the row data
                $rows = array();

                // read in the CSV
                while (($row = fgetcsv($fh, 500, ",")) !== false)
                {
                    $rows[] = $row;
                }

                // skip header row
                unset($rows[0]);

                // check for null input values
                $this->checkCourseData($rows);

                // get the license numbers from the file
                $license_numbers = hash::extract($rows, '{n}.0');

                // reduce the list to only unique license numbers
                $unique_license_numbers = array_unique($license_numbers);

                // find the license numbers in the DB that match the license numbers in the file
                $this->License = ClassRegistry::init('Licenses.License');
                $found_license_numbers = $this->License->find(
                    'list',
                    array(
                        'fields' => 'license_number',
                        'conditions' => array(
                            'license_number' => $unique_license_numbers
                        ),
                    )
                );

                // collect the numbers from the file that are not in the DB
                $invalid_license_numbers = array_diff($license_numbers, $found_license_numbers);

                if (!empty($invalid_license_numbers))
                {
                    // throw exception when invalid license number found, displaying first invalid number
                    throw new Exception(sprintf("License number (%s) is not a valid license number.  File not uploaded.", $invalid_license_numbers[key($invalid_license_numbers)]));
                }

                // Close File
                if (!fclose($fh))
                {
                    throw new Exception('Course file uploaded, but could not be closed after validation.');
                }
            }
            else
            {
                throw new Exception('Could not open course file for validation processing.  File not uploaded.');
            }
        }
        else
        {
            throw new Exception('Course file could not be found for validation processing.  File not uploaded.');
        }

        return true;
    }

    /**
     * Process a data record from the uploaded file, and throw exceptions where data is bad
     *
     * @param array  $data Data read from the uploaded file.  It's presumed to be a two dimensional array
     * @param string $path The path to the data we are processing
     *
     * @access private
     *
     * @return $path
     */
    private function checkCourseData(array $data, $path = '')
    {
        foreach ($data as $key => $value)
        {
            if (!is_array($value))
            {
                if (is_null($value) || $value == '' || $value == 'null')
                {
                    // throw an exception when a null value is found in the data
                    throw new Exception(sprintf("Invalid course data at row (%s), field (%s).  File not uploaded.", $path+1, $key+1));
                }
            }
            else
            {
                // break down the array
                $this->checkCourseData($value, $path . $key);
            }
        }
    }

    /**
     * Returns the next expiring or last expired course date for a license, which ever is latest.
     *
     * @param arruay $license_id license id
     *
     * @access public
     *
     * @return str
     */
    public function getCourseExpiration($license_id = null)
    {
        $results = $this->calcCourseCredits($license_id);

        return $results['CourseRoster']['expire_date'];
    }

    /**
     * Returns the next expiring or last expired reciprocal date for a license, which ever is latest.
     *
     * This really isn't the place for this, not yet anyway. The reciprocals will be reworked and
     * become their own course roster records. Then this will be the right location for this method.
     * When that does happen this method may not be needed.
     *
     * @param array $license_id license id
     *
     * @access public
     *
     * @return str
     */
    public function getReciprocalExpiration($license_id = null)
    {
        $results = $this->calcCourseCredits($license_id);

        return $results['Reciprocal']['expire_date'];
    }

    /**
     * Returns the next expiring or last expired course/reciprocal date for a license, which ever is latest.
     *
     * @param array $license_id license id
     *
     * @access public
     *
     * @return str
     */
    public function getCourseRefresher($license_id = null)
    {
        // get the dates
        $results = $this->calcCourseCredits($license_id);

        // extract the two dates
        $course_date = $results['CourseRoster']['expire_date'];
        $reciprocal_date = $results['Reciprocal']['expire_date'];

        // return value
        switch (true)
        {
        // only course date has a date
        case $course_date != null && $reciprocal_date == null:
            $ret_val = $course_date;
            break;

        // only reciprocal date has a date
        case $reciprocal_date != null && $course_date == null:
            $ret_val = $reciprocal_date;
            break;

        // both have a date, which is ealier
        case $course_date != null && $reciprocal_date != null:
            $ret_val = (strtotime($course_date) < strtotime($reciprocal_date) ? $course_date : $reciprocal_date);
            break;

        default:
            $ret_val = null;
            break;
        }

        return $ret_val;
    }

    /**
     * Returns whether or not required course credits have been met for a license.
     *
     * @param array $license_id license id
     *
     * @access public
     *
     * @return bool
     */
    public function passCourseCredits($license_id = null)
    {
        $results = $this->calcCourseCredits($license_id);

        return $results['requirement_met'];
    }

    /**
     * Returns whether or not required course credits have been met for a license,
     * the next expiring or last expired course expire date which ever is latest
     * and the next expiring or last expired reciprocal expire date which ever is latest.
     *
     * AKA - Moonwalking method
     *
     * @param array $license_id license id
     *
     * @access public
     *
     * @return array
     */
    public function calcCourseCredits($license_id = null)
    {
        // default expire dates array
        $expire_dates = array();

        // get the license data
        $this->License = ClassRegistry::init('Licenses.License');
        $this->License->includeForeignData = false;
        $license = $this->License->find(
            'first',
            array(
                'conditions' => array('License.id' => $license_id, 'License.foreign_obj' => 'Account'),
                'contain' => array(
                    'Application' => array('Reciprocal'),
                    'LicenseType' => array(
                        'AppLicCreditHour',
                        'CourseCatalog' => array('fields' => array('id'))
                    )
                )
            )
        );

        // get the account data
        $this->Account = ClassRegistry::init('Accounts.Account');
        $account = $this->Account->find(
            'first',
            array(
                'fields' => array('id'),
                'conditions' => array('Account.id' => $license['License']['foreign_key']),
                'contain' => array('CourseRoster' => array('conditions' => array('CourseRoster.expire_date !=' => null), 'CourseSection' => array('CourseCatalog')))
            )
        );

        // extract the license type course catalog ids
        $license_type_course_catalog_ids = Hash::extract($license, 'LicenseType.CourseCatalog.{n}.id');

        // add course dates to the expire_dates array
        foreach ($account['CourseRoster'] as $roster)
        {
            // if the course applies to this license type add it
            if (in_array($roster['CourseSection']['course_catalog_id'], $license_type_course_catalog_ids))
            {
                $expire_dates[$roster['expire_date']][] = array(
                    'type' => 'course',
                    'expire_date' => $roster['expire_date'],
                    'code_hours' => $roster['CourseSection']['CourseCatalog']['code_hours'],
                    'non_code_hours' => $roster['CourseSection']['CourseCatalog']['non_code_hours'],
                    'data' => $roster
                );
            }
        }

        // add reciprocal dates to the expire_dates array]
        if (GenLib::isData($license, 'Application.0', array('id')))
        {
            foreach ($license['Application'][0]['Reciprocal'] as $reciprocal)
            {
                $expire_dates[$reciprocal['expire_date']][] = array(
                    'type' => 'reciprocal',
                    'expire_date' => $reciprocal['expire_date'],
                    'code_hours' => $reciprocal['hours'],
                    'non_code_hours' => 0,
                    'data' => $reciprocal
                );
            }
        }

        // sort the expire_dates array in descending order
        krsort($expire_dates);

        // get the total number of required credits
        $license_type_required_credits = array('code' => 0, 'total' => 0);

        foreach ($license['LicenseType']['AppLicCreditHour'] as $credit_hours)
        {
            // make sure we're getting required credits for the right application type (init, renewal, etc.)
            if ($credit_hours['application_type_id'] == $license['Application'][0]['application_type_id'])
            {
                $license_type_required_credits = array(
                    'code' => $credit_hours['code_hours'],
                    'total' =>$credit_hours['total_hours'],
                );
            }
        }

        // default value for meeting credit requirements
        $requirement_met = false;

        // capture next expiring or last expired course/reciprocal dates
        $captured_dates = array('course' => null, 'reciprocal' => null);

        // running totals
        $running_total = array('code' => 0, 'total' => 0);

        // loop the expire_dates array until we've met the code/total credit requirements
        foreach ($expire_dates as $date => $data)
        {
            // loop the date data, there might be more than on course/reciprocal expiring that day
            foreach ($data as $d)
            {
                // capture the date
                $captured_dates[$d['type']] = $d['data'];

                // add to running total
                $running_total['code'] += $d['code_hours'];
                $running_total['total'] += $d['non_code_hours'] += $d['code_hours'];
            }

            // have we met the requirements
            if ($running_total['code'] >= $license_type_required_credits['code'] && $running_total['total'] >= $license_type_required_credits['total'])
            {
                if ($date >= date('Y-m-d'))
                {
                    $requirement_met = true;
                }
                break;
            }
        }

        // returns array
        return array(
            'requirement_met' => $requirement_met,
            'CourseRoster' => $captured_dates['course'],
            'Reciprocal' => $captured_dates['reciprocal']
        );
    }


    /**
     * Returns Course Roster records limited to license type for an account.
     *
     * @param int $license_type_id license type id
     * @param int $account_id      account id
     *
     * @access public
     *
     * @return array
     */
    public function getByLicenseTypeForAccount($license_type_id, $account_id = null)
    {
        // default return array
        $return = array();

        // find the courses that apply to this license type
        $valid_course_ids = ClassRegistry::init('ContinuingEducation.CourseCatalogsLicenseType')->find(
            'list',
            array(
                'conditions' => array(
                        'license_type_id' => $license_type_id
                ),
                'fields' => array(
                    'course_catalog_id'
                ),
            )
        );

        // get course roster records for account that are valid for license type
        $course_rosters = $this->find(
            'all',
            array(
                'contain' => array(
                    'CourseSection' => array(
                        'CourseCatalog',
                        'TrainingProvider'
                    ),
                ),
                'conditions' => array(
                    'CourseRoster.account_id' => $account_id,
                    'CourseSection.course_catalog_id' => $valid_course_ids
                )
            )
        );

        // format data to mimic how containing it in account find would show it.
        foreach ($course_rosters as $key => $course_roster)
        {
            $roster_record = $course_roster['CourseRoster'];
            unset($course_roster['CourseRoster']);
            $return[$key] = array_merge($roster_record, $course_roster);
        }

        return $return;
    }

    /**
     * Adds the provided system note to the specified account
     *
     * @param int $id - the course roster id
     * @param int $account_id - the target account
     * @param string $msg - the message to add to the target account
     *
     * @access public
     *
     * @return bool
     */
    public function addCourseRosterSysNote($account_id = null, $msg = null)
    {
        if (!is_null($account_id) && !is_null($msg))
        {
            $this->Account->Note->sysNote(
                $account_id,                // id
                'Accounts',                 // foreign plugin
                'Account',                  // foreign object
                $account_id,                // foreign key
                $msg                        // note to be added
            );

            return true;
        }

        return false;
    }
}