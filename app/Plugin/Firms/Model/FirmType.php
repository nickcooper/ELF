<?php
/**
 * FirmType model
 *
 * @package Firms.Model
 * @author  Iowa Interactive, LLC.
 */
class FirmType extends FirmsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'FirmType';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * hasMany associations
     *
     * @var array
     * @access public
     */
    public $hasMany = array(
        'Firm' => array(
            'className' => 'Firms.Firm',
            'foreignKey' => 'firm_type_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    /**
     * Add a firm type.
     *
     * @param Array $data Firm type data
     *
     * @return boolean True or false
     * @throws Exception If firm type could not be saved to the database.
     * @access public
     */
    public function addFirmType($data)
    {
        try
        {
            return parent::add($data);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm Type (%s) could not be created.'), $data['FirmType']['name']));
        }
    }

    /**
     * Modify a firm type.
     *
     * @param Array $data Firm type data
     *
     * @return boolean True or false
     * @throws Exception If primary key (`id`) not found in $data or firm type could not be saved to the database.
     * @access public
     */
    public function editFirmType($data)
    {
        if (empty($data['FirmType']['id']))
        {
            throw new Exception(sprintf(__('Missing primary key data for model %s'), $this->name));
        }

        try
        {
            return parent::edit($data);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm Type (%s) could not be modified.'), $data['FirmType']['name']));
        }
    }

    /**
     * Delete a firm type.
     *
     * @param int $id Firm Type ID
     *
     * @return boolean True or false
     * @throws Exception If firm cannot be deleted from database.
     * @access public
     */
    public function deleteFirmType($id)
    {
        try
        {
            return parent::delete($id);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf(__('Firm Type (%s) could not be deleted.'), $id));
        }
    }
}