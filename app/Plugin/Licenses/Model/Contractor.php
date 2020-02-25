<?php
/**
 * Contractor model
 *
 * @package License.Model
 * @author  Iowa Interactive, LLC.
 */
class Contractor extends LicensesAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Contractor';

    /**
     * belongsTo associations
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'License' => array(
            'className'  => 'Licenses.License',
            'foreignKey' => 'license_id',
        ),
    );

    var $validate = array(
        'fin' => array(
            'length' => array(
                'rule' => '/^[0-9]{9}$/',
                'message' => 'Must be 9 digits long.',
            ),
        ),
    );

    /**
     * beforeSave Callback
     *
     * @param array $options Options
     *
     * @return boolean
     * @access public
     */
    public function beforeSave($options = array())
    {
        // create fin last four
        if (strlen($this->data['Contractor']['fin']) > 0)
        {
            $this->data['Contractor']['fin_last_four'] = substr($this->data['Contractor']['fin'], -4);
        }
        // encrypt the FIN
        if (isset($this->data['Contractor']['fin']) && strlen($this->data['Contractor']['fin']) > 0)
        {
            $this->data['Contractor']['fin'] = GenLib::encryptString($this->data['Contractor']['fin']);
        }
        else
        {
            unset($this->data['Contractor']['fin']);
        }

        return parent::beforeSave($options = array());
    }

    /**
     * isOwnerOrManager method
     *
     * checks to see if Auth user has access to data
     *
     * @param int $id License id
     *
     * @return bool
     */
    public function isOwnerOrManager($id = null)
    {
        if (!$id)
        {
            return false;
        }

        $license = $this->findById($id, array('foreign_plugin', 'foreign_obj', 'foreign_key'));

        $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $license['License']['foreign_plugin'], $license['License']['foreign_obj']));

        return $ForeignModel->isOwnerOrManager($license['License']['foreign_key']);
    }
}