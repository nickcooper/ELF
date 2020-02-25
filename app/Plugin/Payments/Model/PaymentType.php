<?php
/**
 * PaymentType model
 *
 * Extends the AppModel.
 *
 * @package Payment.Model
 * @author  Iowa Interactive, LLC.
 */
class PaymentType extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'PaymentType';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'label';

    /**
     * getLicenseTypeId method
     *
     * @param int $id license type ID expected
     *
     * @return array
     */

    public function getPaymentTypeById ($id = null)
    {
        // contain
        $contian = array();

        // return results
        return $this->find(
            'first',
            array(
            'conditions' => array('PaymentType.id' => $id),
            'contain' => $contain
            )
        );
    }

    /**
     * getList method
     *
     * @param array $conditions list of conditions to be used in the parent getList call
     * @param array $options    list of options to be used in the parent getList call
     *
     * @return array returns an array from the parent getList call
     */
    public function getList($conditions = null, $options = null)
    {
        // make sure options is an array
        if (empty($options))
        {
            $options = array();
        }

        // if there wasn't an empty option passed set it to be empty by default
        if (!isset($options['empty']))
        {
            $options['empty'] = '';
        }

        return parent::getList($conditions, $options);
    }
}