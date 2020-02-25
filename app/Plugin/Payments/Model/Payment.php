<?php
/**
* Payment Model
*
* Extends the AppMode. Responsible for the payment data.
* Will represent all the data we need to create, edit or delete.
*
* @package Payments.Model
* @author  Iowa Interactive, LLC.
*/
class Payment extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Payment';

    /**
     * Display field
     *
     * @var string
     * @access public
     */
    public $displayField = 'account_id';

    /**
     * Order
     *
     * @var array
     * @access public
     */
    public $order = array('Payment.account_id' => 'ASC');

    public $actsAs = array(
        'Searchable.Searchable',
    );

    public $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id',
        ),
        'Fee' => array(
            'className' => 'Payments.Fee',
            'foreignKey' => 'account_id',
        ),
        'PaymentType' => array(
            'className' => 'Payments.PaymentType',
            'foreignKey' => 'payment_type_id',
        ),
    );

    public $hasMany = array(
        'PaymentItem' => array(
            'className' => 'Payments.PaymentItem',
            'foreignKey' => 'payment_id',
        )
    );

    /**
     * buildReceipt method
     *
     * @param int $receipt_id the receipt record
     *
     * @return void
     */
    public function buildReceipt ($receipt_id = null)
    {
        $items = $this->getReceiptItems($receipt_id);

        // format the data for the shopping cart view
        $receipt_line_items = array(
            'PaymentItem' => array(),
            'sub_total' => 0,
            'tax_total' => 0,
            'final_total' => 0
        );

        foreach ($items as $key => $item_group)
        {
            foreach ($item_group as $item)
            {
                switch ($key)
                {
                // tax_fees
                case 'tax':
                    $receipt_line_items['tax_total'] +=
                        $receipt_line_items['tax_total']+$item['PaymentItem']['fee'];
                    break;

                // PaymentItem
                default:
                    $tmp_item = array();

                    $tmp_item['id'] = $item['PaymentItem']['id'];
                    $tmp_item['label'] = $item['PaymentItem']['label'];
                    $tmp_item['owner'] = $item['PaymentItem']['owner'];
                    $tmp_item['fee'] = $item['PaymentItem']['fee'];
                    $tmp_item['type'] = $item['PaymentItem']['fee_type'];
                    $tmp_item['descr'] = '';
                    $tmp_item['removable'] = null;
                    $tmp_item['Modifier'] = $item['Modifier'];

                    // add the modifier fee to the subtotal
                    foreach ($item['Modifier'] as $modifier)
                    {
                        $receipt_line_items['sub_total'] += $modifier['fee'];
                    }

                    // assign item to cart_line_items array
                    $receipt_line_items[$key][] = $tmp_item;

                    // add fee to the running sub_total
                    $receipt_line_items['sub_total'] += $item['PaymentItem']['fee'];
                    break;
                }
            }
        }

        // add the tax to the sub_total for final_total
        $receipt_line_items['final_total'] += $receipt_line_items['sub_total'] + $receipt_line_items['tax_total'];

        // format the numbers
        $receipt_line_items['sub_total'] = round($receipt_line_items['sub_total'], 2);
        $receipt_line_items['tax_total'] = round($receipt_line_items['tax_total'], 2);
        $receipt_line_items['final_total'] = round($receipt_line_items['final_total'], 2);

        return $receipt_line_items;
    }

    /**
     * getReceiptItems
     *
     * @param int $payment_id id of payment
     *
     * @return void
     */
    public function getReceiptItems ($payment_id = null)
    {
        try
        {
            $today = date('Y-m-d');

            $payment_items =  $this->PaymentItem->find(
                'all',
                array(
                    'conditions' => array(
                        'PaymentItem.payment_id' => $payment_id,
                        'PaymentItem.fee_type NOT' => array('modifier', 'tax')
                    ),
                    'contain' => array('Modifier')
                )
            );

            $tax_fees = $this->PaymentItem->find(
                'all',
                array(
                    'conditions' => array(
                        'PaymentItem.payment_id' => $payment_id,
                        'PaymentItem.fee_type' => 'tax',
                    )
                )
            );

            return array('PaymentItem' => $payment_items, 'tax' => $tax_fees);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * getLicenseTypeList method
     *
     * @return array returns a list of license types
     */
    public function getPaymentTypeList()
    {
        return $this->PaymentType->getList();
    }

    /**
     * createPaymentFromPendingPayment
     *
     * @param int $pending_payment_id id of payment
     *
     * @return void
     */
    public function createPaymentFromPendingPayment($pending_payment_id)
    {
        // get the pending payment
        $this->PendingPayment = ClassRegistry::init('Payments.PendingPayment');
        $pending_payment = $this->PendingPayment->find(
            'first',
            array(
                'conditions' => array('id' => $pending_payment_id),
                'contain' => array('PendingPaymentItem' => array('Modifier')),
            )
        );

        if (empty($pending_payment))
        {
            throw new Exception('Pending payment not found');
        }

        // temporarily unset data we don't want to transfer to the payment table
        $tmp_pending_payment = $pending_payment;
        unset($tmp_pending_payment['PendingPayment']['id']);
        unset($tmp_pending_payment['PendingPayment']['created']);
        unset($tmp_pending_payment['PendingPayment']['modified']);

        // update the payment data with the pending payment data
        $payment = array('Payment' => $tmp_pending_payment['PendingPayment']);

        // save the payment
        $this->saveAll($payment);
        $payment = $this->read();

        // build the payment items array
        $payment_items = array();

        // loop the items
        foreach ($pending_payment['PendingPaymentItem'] as $key => $item)
        {
            // temporarily unset data we don't want to transfer to the payment items table
            $tmp_item = $item;
            unset($tmp_item['id']);
            unset($tmp_item['pending_payment_id']);
            unset($tmp_item['created']);
            unset($tmp_item['modified']);

            $tmp_item['payment_id'] = $payment['Payment']['id'];

            // update the payment data with the pending payment item data
            $payment_items[$key]['PaymentItem'] = $tmp_item;

            foreach ($item['Modifier'] as $m_key => $modifier)
            {
                // temporarily unset data we don't want to transfer to the payment items table
                $tmp_modifier = $modifier;
                unset($tmp_modifier['id']);
                unset($tmp_modifier['pending_payment_id']);
                unset($tmp_modifier['foreign_obj']);
                unset($tmp_modifier['foreign_key']);
                unset($tmp_modifier['created']);
                unset($tmp_modifier['modified']);

                // set the new foreign obj data
                $tmp_modifier['payment_id'] = $payment['Payment']['id'];
                $tmp_modifier['foreign_obj'] = 'PaymentItem';

                // update the payment data with the pending payment item data
                $payment_items[$key]['PaymentItem']['Modifier'][$m_key] = $tmp_modifier;
            }
        }

        // save payment items
        if ($this->PaymentItem->saveMany($payment_items, array('deep' => true)))
        {

            if ($this->runPaymentItemCallbacks($this->id))
            {
                $this->contain(array('PaymentItem' => array('Modifier')));
                return $this->read();

            }
        }

        return false;
    }

    /**
     * Calls afterPayment callback for each payment item associated to a payment
     *
     * @param int $payment_id id of payment
     *
     * @return void
     *
     * @todo
     * - Have this return true or false (throw an exception?) based on the return from each of the payment items
     * callbacks
     */
    private function runPaymentItemCallbacks($payment_id)
    {
        $payment_items = $this->PaymentItem->find(
            'all',
            array(
                'conditions' => array(
                    'PaymentItem.payment_id' => $payment_id,
                )
            )
        );

        foreach ($payment_items as $payment_item)
        {
            // Load foreign model
            $ForeignModel = ClassRegistry::init(sprintf('%s.%s', $payment_item['PaymentItem']['foreign_plugin'], $payment_item['PaymentItem']['foreign_obj']));

            // If foreign model's afterPayment method exists
            if ($ForeignModel->hasMethod('afterPayment'))
            {
                $ForeignModel->afterPayment($payment_item['PaymentItem']);
            }
        }

        return true;
    }
}
