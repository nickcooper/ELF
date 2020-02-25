<?php

App::uses('ImportTask', 'Console/Command/Task');

/**
 * ImportGroupPaymentTypeTask
 *
 * @package    App
 * @subpackage App.Console.Command.Task
 * @author     Iowa Interactive, LLC.
 */
class ImportGroupPaymentTypeTask extends ImportTask
{
    /**
     * Models our task has access to.
     *
     * @var array
     * @access public
     */
    public $uses = array('AppModel', 'Accounts.Group', 'Payments.PaymentType', 'GroupsPaymentType');

    /**
     * Primary save model
     *
     * @var str
     * @access public
     */
    public $primary_model = 'GroupsPaymentType';

    /**
     * Data mapping from csv to cake models
     *
     * @var array
     * @access public
     */
    public $data_map = array(
        0 => 'GroupsPaymentType.group_id',
        1 => 'GroupsPaymentType.payment_type_id'
    );

    /**
     * Import a single row of data
     *
     * @param arrary $row array of row data to import
     *
     * @return void
     * @access public
     */
    public function importRow($row)
    {
        $group = $this->Group->findByLabel($row[0]);

        if (!$group)
        {
            throw new Exception(sprintf("`%s` not found in groups", $row[0]));
        }

        $row[0] = $group['Group']['id'];

        $payment_type = $this->PaymentType->findByLabel($row[1]);

        if (!$payment_type)
        {
            throw new Exception(sprintf("`%s` not found in payment types", $row[1]));
        }

        $row[1] = $payment_type['PaymentType']['id'];


        parent::importRow($row);
    }
}
