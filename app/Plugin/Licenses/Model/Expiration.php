<?php
/**
 * Expiration model
 *
 * @package Licenses.Model
 * @author  Iowa Interactive, LLC.
 */
class Expiration extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Expiration';

    var $useTable = null;

    var $displayField = 'expire_date';

    var $belongsTo = array(
        'License' => array(
            'className' => 'Licenses.License',
            'foreignKey' => 'parent_key',
            'conditions' => array('Expiration.parent_obj' => 'License'),
        ),
    );

    /**
     * afterSave method
     *
     * @param array $created this is handled by cakephp, true if created new record, false if updated a record
     *
     * @return true
     */
    public function afterSave($created)
    {
        // update the license expiration date
        if (!$this->_updateLicenseExpiration($this->data['Expiration']['parent_key']))
        {
            return false;
        }

        return true;
    }

    /**
     * afterDelete method
     *
     * @return true
     */
    public function afterDelete()
    {
        // update the license expiration date
        if (!$this->_updateLicenseExpiration($this->data['Expiration']['parent_key']))
        {
            return false;
        }

        return true;
    }

    /**
     * _updateLicenseExpiration method
     *
     * Gets the next occurring expiration date and
     * updates the expiration date in the license
     * record.
     *
     * @param int $license_id expecting the license record id
     *
     * @return true
     * @access private
     */
    private function _updateLicenseExpiration ($license_id = null)
    {
        // get expiration dates
        if ($expiration = $this->_getNextExpiration($license_id))
        {
            // assign the license id
            $this->License->set('id', $license_id);
            $this->License->set('expire_date', $expiration['Expiration']['expire_date']);

            //debug($this->License->data); exit();

            // update the license record
            if ($this->License->save())
            {
                // pass
                return true;
            }
        }

        // fail
        return false;
    }

    /**
     * getExpirations method
     *
     * Get all expiring dates for license.
     *
     * @param str $parent_obj expecting the parent object
     * @param int $parent_key expecting the parent object record id
     *
     * @return array returns all expiration records
     * @access public
     */
    public function getExpirations ($parent_obj = null, $parent_key = null)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'Expiration.parent_obj' => $parent_obj,
                    'Expiration.parent_key' => $parent_key,
                    'DATE(Expiration.expire_date)'
                ),
                'order' => array('Expiration.expire_date' => 'ASC')
            )
        );
    }

    /**
     * _getNextExpiration method
     *
     * Get the next expiring date for license.
     *
     * @param int $license_id expecting the license record id
     *
     * @return array returns an expiration record
     * @access private
     */
    private function _getNextExpiration ($license_id = null)
    {
        // return the earliest expiration for this license id
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'Expiration.parent_obj' => 'License',
                    'Expiration.parent_key' => $license_id,
                    'DATE(Expiration.expire_date)'
                ),
                'order' => array('Expiration.expire_date' => 'ASC')
            )
        );
    }

    /**
     * _getRefresherExpiration method
     *
     * @param int $license_id expecting the license record id
     *
     * @return array returns the refresher expiration record
     * @access private
     */
    private function _getRefresherExpiration ($license_id = null)
    {
        // return the refresher expiration for this license id
        return $this->find(
            'first',
            array(
                'conditions' => array(
                    'Expiration.parent_obj' => 'License',
                    'Expiration.parent_key' => $license_id,
                    'DATE(Expiration.expire_date)',
                    'Expiration.foreign_obj' => 'CourseCatalog'
                ),
                'order' => array('Expiration.expire_date' => 'ASC')
            )
        );
    }


    /**
     * getPastExpirations method
     *
     * Get all expiring dates for license.
     *
     * @param str $parent_obj expecting the parent object
     * @param int $parent_key expecting the parent object record id
     *
     * @return array returns all expiration records prior to today
     * @access public
     */
    public function getPastExpirations ($parent_obj = null, $parent_key = null)
    {
        return $this->find(
            'all',
            array(
                'conditions' => array(
                    'Expiration.parent_obj' => $parent_obj,
                    'Expiration.parent_key' => $parent_key,
                    'DATE(Expiration.expire_date)',
                    'DATE(Expiration.expire_date) <= DATE(NOW())',
                ),
                'order' => array('Expiration.expire_date' => 'DESC')
            )
        );
    }
}