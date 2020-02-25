<?php
/**
 * Reciprocal model
 *
 * Extends the AppModel. Responsible for managing third party test data.
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class Reciprocal extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Reciprocal';

    var $displayField = 'label';

    var $actsAs = array(
        'Uploads.Upload' => array(
            'Transcript' => array(
                'save_location' => 'files',
                'allowed_types' => array('image/jpeg', 'application/pdf'),
                'association' => 'hasOne',
            ),
        ),
    );

    var $belongsTo = array(
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'application_id'
        ),
    );

    var $hasOne = array(
        'Address' => array(
            'className' => 'AddressBook.Address',
            'foreignKey' => 'foreign_key',
            'conditions' => array('Address.foreign_obj' => 'Reciprocal'),
        ),
    );

    var $hasMany = array(
        'Transcript' => array(
            'className' => 'Uploads.Upload',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'Transcript.foreign_obj' => 'Reciprocal',
                'Transcript.identifier' => 'Transcript'
            )
        ),
    );

    var $validate = array(
        'expire_date' => array(
            'future' => array(
                'rule' => 'validateFutureDate',
                'message' => 'Course expiration must be a future date.'
            ),
        ),
    );

    /**
     * Custom validation for future date
     *
     * @param array $field  field
     * @param array $params params
     *
     * @return bool
     * @access public
     */
    public function validateFutureDate($field, $params)
    {
        // can we convert the value to a timestamp?
        if ($expire_date = strtotime($field['expire_date']))
        {
            // is the time stamp a future date?
            if ($expire_date > time())
            {
                return true;
            }
        }

        // fail
        return false;
    }

    /**
     * Add method
     *
     * Adds the reciprocal record and dispatches
     * the Model.Reciprocal.add event.
     *
     * @param array $data    reciprocal data to be saved
     * @param array $options cake save options
     *
     * @return true
     * @access public
     */
    public function add ($data, $options=array())
    {
        try
        {
            // add the new reciprocal record
            if (parent::add($data, $options))
            {
                // dispatch reprocal add event
                $reciprocal = $this->details($this->getLastInsertId(), array('Application' => 'License'));

                $this->dispatch('Model-Reciprocal-add', array('license_id' => $reciprocal['Application']['License']['id']));

                return true;
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Edit method
     *
     * Edits the reciprocal record and dispatches
     * the Model.Reciprocal.edit event.
     *
     * @param array $data    reciprocal data to be saved
     * @param array $options cake save options
     *
     * @return true
     * @access public
     */
    public function edit ($data, $options=array())
    {
        try
        {
            // update the reciprocal record
            if (parent::edit($data, $options))
            {
                // dispatch reprocal edit event
                $reciprocal = $this->details($this->getLastInsertId(), array('Application' => 'License'));

                $this->dispatch('Model-Reciprocal-edit', array('license_id' => $reciprocal['License']['id']));

                return true;
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Delete function overriding delete in parent class
     *
     * Mostly behaves the same as the parent delete function but dispatches an event
     *
     * @param int     $id      id of item to be deleted
     * @param boolean $cascade Set to true to delete records that depend on this record
     *
     * @return boolean True on success
     *
     * @todo throw exception if there is related data
     */
    public function delete($id=null, $cascade=true)
    {
        // get the details first
        $reciprocal = $this->details($id, array('Application' => 'License'));

        if (parent::delete($id, $cascade))
        {
            $this->dispatch('Model-Reciprocal-delete', array('license_id' => $reciprocal['License']['id']));
        }
    }
}