<?php
/**
 * EducationDegree model
 *
 * @package Accounts.Model
 * @author  Iowa Interactive, LLC.
 */
class EducationDegree extends AccountsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'EducationDegree';

    /**
     * Behaviors
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Uploads.Upload' => array(
            'Upload' => array(
                'save_location' => 'files',
                'allowed_types' => array('application/pdf'),
                'association' => 'hasOne',
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Account' => array(
            'className'  => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
        'Degree' => array(
            'className'  => 'Accounts.Degree',
            'foreignKey' => 'degree_id',
        ),
    );

    /**
     * hasOne
     *
     * @var array
     * @access public
     */
    public $hasOne = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'EducationDegree'),
        ),
        'Upload' => array(
            'className'  => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Upload.foreign_obj' => 'EducationDegree',
                'Upload.identifier'  => 'Upload',
            ),
        ),
    );

    /**
     * Validation rules
     *
     * @var array
     * @access public
     */
    public $validate = array(
        'degree_id' => array(
            'rule'    => 'numeric',
            'message' => 'Please select a highest completed education.',
        ),
    );

    /**
     * saveEducation method
     *
     * @param array $data expecting Address model data
     *
     * @return boolean
     * @access public
     */
    public function saveEducation($data = array())
    {
        // if no file uploaded, remove any preset data to prevent partial record from inserting
        if (empty($data['Upload']['file']))
        {
            unset($data['Upload']);
        }
        else
        {
            // set a default label value for the upload if no label value provided
            if (empty($data['Upload']['label']))
            {
                $data['Upload']['label'] = 'Education Uploaded Transcript';
            }
        }

        $education_degrees = $this->find(
            'all',
            array(
                'conditions' => array(
                    'EducationDegree.account_id' => $data['EducationDegree']['account_id'],
                )
            )
        );

        $highest_earned_exists = false;

        foreach ($education_degrees as $education_degree)
        {
            if ($education_degree['EducationDegree']['highest_earned'] == 1)
            {
                $highest_earned_exists = true;
                break;
            }
        }

        // if no existing highest_earned, set new record to highest_earned
        $hasOneDegree = (
            count($education_degrees) == 1 &&
            ! empty($data['EducationDegree']['id']) &&
            $education_degrees[0]['EducationDegree']['id'] == $data['EducationDegree']['id']
        );

        if (! $highest_earned_exists || $hasOneDegree)
        {
            $data['EducationDegree']['highest_earned'] = '1';
        }

        // if existing highest_earned, unset highest_earned for all associated records
        if ($highest_earned_exists && $data['EducationDegree']['highest_earned'] == '1')
        {
            //loop through the highest_earned
            foreach ($education_degrees as $education_degree)
            {
                if ($education_degree['EducationDegree']['highest_earned'] == '1')
                {
                    $this->create();
                    $this->id = $education_degree['EducationDegree']['id'];

                    // save each updated highest_earned flag
                    if (!$this->saveField('highest_earned', 0))
                    {
                        $this->Session->setFlash(__('Failed to remove existing highest earned designation.'));
                    }
                }
            }
        }

        // save the data
        if (! empty($data['EducationDegree']['id']))
        {
            if ($this->edit($data))
            {
                return true;
            }
        }
        else
        {
            if ($this->add($data))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id EducationDegree id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        return $this->hasAny(
            array(
                'EducationDegree.id' => $id,
                'EducationDegree.account_id' => CakeSession::read("Auth.User.id")
            )
        );
    }
}