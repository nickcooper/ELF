<?php
/**
* ShoppingCart Model
*
* Extends the AppModel. Responsible for the payment data.
* Will represent all the data we need to create, edit or delete.
*
* @package ShoppingCart.Model
* @author  Iowa Interactive, LLC.
*/
class ShoppingCart extends PaymentsAppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'ShoppingCart';

    public $actsAs = array(
        'Payments.Payable',
    );

    var $belongsTo = array(
        'Account' => array(
            'className' => 'Accounts.Account',
            'foreignKey' => 'account_id'
        ),
        'Fee' => array(
            'className' => 'Payments.Fee',
            'foreignKey' => 'fee_id',
        ),
        'Application' => array(
            'className' => 'Licenses.Application',
            'foreignKey' => 'foreign_key',
            'conditions' => array(
                'ShoppingCart.foreign_plugin' => 'Licenses',
                'ShoppingCart.foreign_obj' => 'Application'
            )
        )
    );

    /**
     * beforeSave Callback
     *
     * @param array $options array of options to be passed to the Parent beforeSave
     *
     * @return boolean
     *
     * @todo
     * - We should probably be throwing the below exception as a part of the add rather than the beforeSave
     */
    public function beforeSave($options = array())
    {
        $data = $this->data['ShoppingCart'];
        unset($data['modified']);
        unset($data['created']);

        // Number of records in the db that match so determine record already exists.
        if ($this->find('count', array('conditions' => $data)))
        {
            throw new Exception('You already have this item in your shopping cart');
        }

        return parent::beforeSave($options);
    }

    /**
     * getForeignData
     *
     * @param var $result Contains the data array
     *
     * @return $foreign_data
     */
    private function _getForeignData($result = array())
    {
        // add foreign object data to record
        if (!isset($this->{$result['foreign_obj']}))
        {
            $foreign_obj = $result['foreign_obj'];
            $this->ForeignObj = ClassRegistry::init($foreign_obj);
        }

        // Find shoppingCart foreign_model data.
        $foreign_data = $this->ForeignObj->find(
            'first',
            array(
                'conditions' => array(
                    sprintf('%s.id', $foreign_obj) => $result['foreign_key']
                )
            )
        );

        // Return foreign_data to result
        return $foreign_data[$foreign_obj];
    }

    /**
     * This will add a new item to the shoppingCart
     * The function will be called for other plugin and call the add function
     *
     * @param int    $account_id     This is the account id
     * @param string $foreign_plugin The fp being pass to add the data
     * @param string $foreign_obj    The fo being add to be pass to add the data
     * @param int    $foreign_key    The fk add as a param to add data
     * @param int    $fee            This is the fee to the license type pulled from database
     * @param string $label          The label field of the shopping cart data
     *
     * @return void
     * @access public
     */
    public function addItem($account_id, $foreign_plugin, $foreign_obj, $foreign_key, $fee, $label = null, $owner = null)
    {
        try
        {
            $data = array(
                'ShoppingCart' => array(
                    'account_id' => $account_id,
                    'foreign_plugin' => $foreign_plugin,
                    'foreign_obj' => $foreign_obj,
                    'foreign_key' => $foreign_key,
                    'fee_id' => $fee,
                    'label' => $label,
                    'owner' => $owner
                )
            );

            $this->add($data);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Get fee
     *
     * @param str $fee_key This is the key to look up a fee
     *
     * @return array
     */
    public function getFeeByKey($fee_key = null)
    {
        try
        {
            // load the Fee model
            $this->Fee = ClassRegistry::init('Payments.Fee');

            // get and return the fees
            return $this->Fee->getFeeByKey($fee_key);
        }
        catch (Exception $e)
        {
            throw new Exception(sprintf('Failed to retrieve payment item fee. (%s)', $e->getMessage()));
        }
    }

    /**
     * getItems method
     *
     * Gets shopping cart items, applies fee modifiers
     * to items and calculates the adjusted item totals.
     *
     * @param int $account_id the unique id of the account
     *
     * @return an array of line items for the cart
     */
    public function buildCart ($account_id = null)
    {
        try
        {
            // validate account id
            if (!$account = $this->Account->findById($account_id))
            {
                throw new Exception('Could not find account information.');
            }

            // get items for account id
            $items = $this->getCartItems($account_id);

            // format the data for the shopping cart view
            $cart_line_items = array(
                'PaymentItem' => array(),
                'sub_total' => 0,
                'tax_total' => 0,
                'final_total' => 0
            );

            // is there a tax rate?
            $tax_rate = Configure::read('Configuration.tax_percentage');

            // loop the items
            foreach ($items['PaymentItem'] as $key => $item)
            {
                $tmp_item = array();

                $tmp_item['id'] = $item['ShoppingCart']['id'];
                $tmp_item['label'] = $item['ShoppingCart']['label'];
                $tmp_item['owner'] = $item['ShoppingCart']['owner'];
                $tmp_item['foreign_plugin'] = $item['ShoppingCart']['foreign_plugin'];
                $tmp_item['foreign_obj'] = $item['ShoppingCart']['foreign_obj'];
                $tmp_item['foreign_key'] = $item['ShoppingCart']['foreign_key'];
                $tmp_item['fee'] = (isset($item['Fee']['fee'])) ? $item['Fee']['fee'] : 0;
                $tmp_item['fee_id'] = (isset($item['Fee']['id'])) ? $item['Fee']['id'] : null;
                $tmp_item['descr'] = '';
                $tmp_item['removable'] = (isset($item['Fee']['removable'])) ? $item['Fee']['id'] : 1;
                $tmp_item['Modifier'] = array();

                // get the item fee modifiers
                $modifiers = array();

                if (isset($item['Fee']['id']))
                {
                    if ($item['ShoppingCart']['foreign_obj'] != 'Application')
                    {
                        // non license fee modifiers
                        $modifiers = $this->Fee->FeeModifier->find(
                            'all',
                            array(
                                'conditions' => array('FeeModifier.fee_id' => $item['Fee']['id'])
                            )
                        );
                    }
                    else
                    {
                        // if license fee add license id to data
                        $tmp_item['license_id'] = $item['Application']['license_id'];

                        // add the license number as the descr
                        $tmp_item['descr'] = $item['Application']['License']['license_number'];

                        // make sure we have an expiration date
                        $expire_date = $item['Application']['expire_date'];

                        if (!strtotime($expire_date))
                        {
                            // grab the license type record static expiration
                            $static_expiration = $item['Application']['License']['LicenseType']['static_expiration'];

                            // use the static expiration if it's valid
                            if (strtotime($static_expiration) && !preg_match('/0000-00-00/', $static_expiration))
                            {
                                $expire_date = $static_expiration;
                            }
                        }

                        // license application fee modifiers
                        $modifiers = $this->Fee->FeeModifier->find(
                            'all',
                            array(
                                'conditions' => array(
                                    "FeeModifier.fee_id = ".$item['Fee']['id']."
                                    AND
                                    (
                                        (FeeModifier.start_range IS NULL AND FeeModifier.end_range IS NULL)
                                        OR
                                        NOW() BETWEEN
                                            CASE WHEN FeeModifier.start_range LIKE '%day%'
                                                THEN DATE_ADD(
                                                    DATE('".$expire_date."'),
                                                    INTERVAL REPLACE(
                                                        REPLACE(
                                                            FeeModifier.start_range, 'day', ''), ' s', '') DAY
                                                )
                                            WHEN FeeModifier.start_range LIKE '%month%'
                                                THEN DATE_ADD(
                                                    DATE('".$expire_date."'),
                                                    INTERVAL
                                                        REPLACE(
                                                            REPLACE(
                                                                FeeModifier.start_range, 'month', ''), ' s', '') MONTH
                                                )
                                            END
                                        AND
                                            CASE WHEN FeeModifier.end_range LIKE '%day%'
                                                THEN DATE_ADD(
                                                    DATE('".$expire_date."'),
                                                    INTERVAL
                                                        REPLACE(
                                                            REPLACE(
                                                                FeeModifier.end_range, 'day', ''), ' s', '') DAY
                                                )
                                            WHEN FeeModifier.end_range LIKE '%month%'
                                                THEN DATE_ADD(
                                                    DATE('".$expire_date."'),
                                                    INTERVAL
                                                        REPLACE(
                                                            REPLACE(
                                                                FeeModifier.end_range, 'month', ''), ' s', '') MONTH
                                                )
                                            END
                                    )",
                                )
                            )
                        );
                    }
                }

                // add modifiers to the data array and adjust the subtotal
                $adjusted_fee = (isset($item['Fee']['fee'])) ? $item['Fee']['fee'] : 0;
                foreach ($modifiers as $modifier)
                {
                    $tmp_item['Modifier'][] = array(
                        'label' => $modifier['FeeModifier']['label'],
                        'modifier_id' => $modifier['FeeModifier']['id'],
                        'fee' => round($modifier['FeeModifier']['fee'], 2)
                    );

                     $adjusted_fee += $modifier['FeeModifier']['fee'];
                }

                // add item adjusted total to sub_total
                $cart_line_items['sub_total'] += $adjusted_fee;

                // is there a tax to apply?
                if ($tax_rate && isset($item['Fee']['apply_tax']) && $item['Fee']['apply_tax'])
                {
                    $cart_line_items['tax_total'] += $tax_rate * $adjusted_fee;
                }

                // assign item to cart_line_items array
                $cart_line_items['PaymentItem'][] = $tmp_item;
            }

            // calculate the sub_total plus the tax_total = final_total
            $cart_line_items['final_total'] = $cart_line_items['sub_total'] + $cart_line_items['tax_total'];

            // format the numbers
            $cart_line_items['sub_total'] = round($cart_line_items['sub_total'], 2);
            $cart_line_items['tax_total'] = round($cart_line_items['tax_total'], 2);
            $cart_line_items['final_total'] = round($cart_line_items['final_total'], 2);

            return $cart_line_items;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * getCartItems
     *
     * Will get the individual details for the cart items in the cart
     *
     * @param int $account_id The account_id
     *
     * @return an array of PaymentItems
     */
    public function getCartItems ($account_id)
    {
        try
        {
            $today = date('Y-m-d');

            $payment_items =  $this->find(
                'all',
                array(
                    'conditions' => array(
                        'ShoppingCart.account_id' => $account_id,
                    ),
                    'contain' => array('Application' => array('License' => array('LicenseType')), 'Fee'),
                    'order' => array('FIELD("ShoppingCart.foreign_obj", "Application")')
                )
            );

            return array('PaymentItem' => $payment_items);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * Will get the total price for all items in cart
     *
     * @param int $account_id The account_id
     *
     * @return total
     */
    public function getTotal($account_id)
    {
        $total = $this->find(
            'all',
            array(
            'conditions' => array('account_id' => $account_id),
            'fields' => array('sum(Fee.fee) AS total'),
            'contain' => array('Fee')
        )
        );

        $total = $total[0][0]['total'];
        return $total;
    }

    /**
     * getTotal Method
     * Will get the total price for all items in cart
     *
     * @param int $account_id This is the account id
     *
     * @return total
     */
    public function cartCount($account_id)
    {
        return $this->find(
            'count',
            array(
                'conditions' => array(
                    'account_id' => $account_id
                )
            )
        );
    }

    /**
     * getItemsForUsers Method
     * Will all the items the user has in a
     * shopping cart and return them in the results varaible
     *
     * @param int $account_id This is the account id
     *
     * @access public
     *
     * @return $results
     */
    public function getItemForUser($account_id)
    {
        $results = $this->find(
            'all',
            array(
                'conditions' => array('account_id' => $account_id),
        'contain' => array('Fee'))
        );
        return $results;
    }

    /**
    * Remove an item from the shopping cart
    *
    * @param int $id id of the item to remove from the shopping cart
    *
    * @return boolean
    */
    function removeItem($id)
    {
        return $this->delete($id);
    }

    /**
     * Remove all items from the shopping cart for a user
     *
     * @param int $account_id id of account whose shopping cart you want to clear out
     *
     * @return boolean success
     */
    public function emptyCart($account_id)
    {
        $this->deleteAll(
            array(
                'account_id' => $account_id,
            )
        );
    }

    /**
     * Takes an account id and creates a pending payment including each of the items in that users shopping cart
     *
     * @param int   $account_id               id of user to create a pending payment for
     * @param int   $payment_type_id          id for the payment type that is being used
     * @param date  $payment_date             date the payment was made
     * @param date  $payment_received_date    date the payment was received by the agency
     * @param float $amount_paid              amount paid
     * @param int   $payment_identifier       the unique identifier for a payment
     * @param char  $payment_transaction_data serialized transaction data
     *
     * @throw exception if shopping cart is empty
     *
     * @return boolean success
     */
    public function createPendingPaymentForUser($account_id, $payment_type_id, $payment_date = null, $payment_received_date = null, $amount_paid = null, $payment_identifier = '', $payment_transaction_data = null)
    {
        // get the shopping cart items
        $payment_items = $this->buildCart($account_id);

        // if there was nothing there throw an exception
        if (empty($payment_items))
        {
            throw new Exception('Failed to get payment items.');
        }

        // load the pending payment model
        $this->PendingPayment = ClassRegistry::init('Payments.PendingPayment');

        // format for pending payment save
        $pending_payment = $this->PendingPayment->create();
        $pending_payment['PendingPayment']['account_id'] = $account_id;
        $pending_payment['PendingPayment']['payment_type_id'] = $payment_type_id;
        $pending_payment['PendingPayment']['total'] = $payment_items['final_total'];
        $pending_payment['PendingPayment']['payment_date'] = $payment_date;
        $pending_payment['PendingPayment']['payment_received_date'] = $payment_received_date;
        $pending_payment['PendingPayment']['amount_paid'] = $amount_paid;
        $pending_payment['PendingPayment']['local_transaction_id'] = date('YmdHisB') . '-' . $account_id;
        $pending_payment['PendingPayment']['transaction_data'] = serialize($payment_transaction_data);
        $pending_payment['PendingPayment']['identifier'] = $payment_identifier;

        // loop the payment items
        foreach ($payment_items as $payment_group => $items)
        {
            if (in_array($payment_group, array('sub_total', 'tax_total', 'final_total')))
            {
                continue;
            }

            foreach ($items as $item)
            {
                // format item for pending payment save
                $pending_payment['PendingPaymentItem'][$item['id']] = array(
                    'label' => $item['label'],
                    'owner' => $item['owner'],
                    'foreign_plugin' => $item['foreign_plugin'],
                    'foreign_obj' => $item['foreign_obj'],
                    'foreign_key' => $item['foreign_key'],
                    'fee' => (isset($item['fee'])) ? $item['fee'] : 0,
                    'fee_type' => '',
                    'fee_data' => (isset($item['fee_id'])) ? serialize($this->Fee->findById($item['fee_id'])) : '',
                );

                // format modifier for pending payment save
                foreach ($item['Modifier'] as $modifier)
                {
                    $pending_payment['PendingPaymentItem'][$item['id']]['Modifier'][] = array(
                        'label' => $modifier['label'],
                        'foreign_plugin' => 'Payments',
                        'foreign_obj' => 'PendingPaymentItem',
                        'fee' => $modifier['fee'],
                        'fee_type' => 'modifier',
                        'fee_data' => serialize($this->Fee->FeeModifier->findById($modifier['modifier_id'])),
                    );
                }
            }
        }

        // add the tax total to the items data
        if ($tax_rate = Configure::read('Configuration.tax_percentage'))
        {
            $pending_payment['PendingPaymentItem'][] = array(
                'label' => 'Tax',
                'foreign_plugin' => 'Configuration',
                'foreign_obj' => 'Configuration',
                'foreign_key' => 0,
                'fee' => $payment_items['tax_total'],
                'fee_type' => 'tax',
                'fee_data' => serialize(
                    array('Configuration' => array('tax_percentage' => Configure::read('Configuration.tax_percentage')))
                ),
            );
        }

        // save
        if ($this->PendingPayment->saveAssociated($pending_payment, array('deep' => true)))
        {
            // assuming successful save clear the shopping cart
            $this->emptyCart($account_id);
        }

        return $this->PendingPayment->id;
    }
}
