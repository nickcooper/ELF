<?php
/**
 *   Class Payments Controller
 *
 * @category Payments
 * @package  Payments.Controller
 * @author   Iowa Interactive, LLC.
 */
class PaymentsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Payments';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.Payment', 'Payments.ShoppingCart', 'Payments.PendingPayment', 'Accounts.Group');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('CommonCheckout.CommonCheckout');

    /**
     * index method
     *
     * @return void
     * @access public
     */
    public function index()
    {
        // we're using the Searchable plugin index (it;s pretty dope)
        $this->redirect(
            array(
                'plugin' => 'searchable',
                'controller' => 'searchable',
                'action' => 'index',
                'fp' => 'payments',
                'fo' => 'payment'
            )
        );
    }

    /**
     * add method
     * This will add the payment form info into the payments table.
     *
     * @param int $payment_type_id the Payment id
     *
     * @access public
     *
     * @return void
     */
    public function add($payment_type_id = null)
    {
        try
        {
            // define the paying account id
            $account_id = $this->Auth->user('id');

            // pass the group id to the view
            $this->set('group_id', $this->Auth->user('group_id'));

            $this->set('is_admin', $this->Auth->user('Group.admin'));

            $this->set('payment_types', $this->Group->getPaymentTypes());

            // define the payment total
            $cart = $this->ShoppingCart->buildCart($account_id);

            // fail if no items to pay for
            if (!GenLib::isData($cart, 'PaymentItem.0', array('id')))
            {
                $this->Session->setFlash('No payment items found.');
                $this->redirect('/accounts/accounts/home');
            }

            $this->set('total', $cart['final_total']);
            $this->set('payment_items', $cart);

            // if total is zero, bypass form and make payment record.
            if ($cart['final_total'] == 0)
            {

                $date = date('Y-m-d H:i:s');
                $this->request->data['Payment']['payment_type_id'] = null;
                $this->request->data['Payment']['payment_date'] = $date;
                $this->request->data['Payment']['payment_received_date'] = $date;
                $this->request->data['Payment']['amount_paid'] = 0;
                $this->request->data['Payment']['identifier'] = '';
                $this->request->data['Payment']['transaction_data'] = null;
            }

            if ($this->request->data)
            {
                $this->Payment->set($this->request->data);
                if ($this->Payment->validates())
                {
                    // create pending payment
                    $pending_payment_id = $this->ShoppingCart->createPendingPaymentForUser(
                        $account_id, $this->request->data['Payment']['payment_type_id'],
                        $this->request->data['Payment']['payment_date'],
                        $this->request->data['Payment']['payment_received_date'],
                        $this->request->data['Payment']['amount_paid'],
                        $this->request->data['Payment']['identifier'],
                        $this->request->data['Payment']['transaction_data']
                    );

                    // create payment from pending payment
                    if ($payment = $this->Payment->createPaymentFromPendingPayment($pending_payment_id))
                    {
                        // redirect to the receipt
                        $this->Session->setFlash(__('The Payment was saved.'));
                        $this->redirect(
                            array(
                                'plugin' => 'payments',
                                'controller' => 'payments',
                                'action' => 'receipt',
                                $this->Payment->id
                            )
                        );
                    }
                }
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }
    }

    /**
     * receipt method
     *
     * @param int|string $id is expected.
     *
     * @return void
     * @access public
     */
    public function receipt($id)
    {
        $this->set('payment_items', $this->Payment->buildReceipt($id));
        $this->set('payment', $this->Payment->find('first', array('conditions' => array('Payment.id' => $id))));
    }

    /**
     * credit_card_success method
     *
     * @return void
     * @access public
     */
    public function credit_card_success()
    {
        try
        {
            $token = $this->request->query['token'];
            $response = $this->CommonCheckout->getPaymentInfo($token);

            $transaction_data = json_decode(json_encode($response->GetPaymentInfoResult), 1);

            // get the pending payment id
            $this->PendingPayment = ClassRegistry::init('Payments.PendingPayment');
            $pending_payment_id = $this->PendingPayment->field(
                'id',
                array(
                    'local_transaction_id' => $transaction_data['LOCALREFID']
                )
            );

            $pending_payment = $this->PendingPayment->find(
                'first',
                array(
                    'conditions' => array(
                        'PendingPayment.local_transaction_id' => $transaction_data['LOCALREFID']
                    )
                )
            );

            $pending_payment_id = $pending_payment['PendingPayment']['id'];

            $paid_date = date(
                'Y:m:d H:i:s',
                strtotime(
                    $transaction_data['RECEIPTDATE'].' '.substr($transaction_data['RECEIPTTIME'], 0, 11)
                )
            );

            $data = array();
            $data['PendingPayment']['transaction_id'] = $transaction_data['ORDERID'];
            $data['PendingPayment']['transaction_data'] = serialize($transaction_data);
            $data['PendingPayment']['amount_paid'] = $transaction_data['TOTALAMOUNT'];
            $data['PendingPayment']['payment_date'] = $paid_date;
            $data['PendingPayment']['payment_received_date'] = $paid_date;

            $this->PendingPayment->id = $pending_payment_id;
            $this->PendingPayment->save($data);


            if ($this->Payment->createPaymentFromPendingPayment($pending_payment_id))
            {
                $this->Session->setFlash(__('The Payment was saved.'));
                $this->redirect(
                    array(
                        'plugin' => 'payments',
                        'controller' => 'payments',
                        'action' => 'receipt',
                        $this->Payment->id
                    )
                );
            }
            else
            {
                throw new Exception('Failed to save payment.');
            }
        }
        catch (Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }
    }

    /**
     * CCP credit card cancel function
     *
     * @return void
     */
    public function credit_card_cancel()
    {
        try
        {
            $this->Session->setFlash(
                'Your payment has been cancelled. If you wish to complete your purchase in the future
                you can add the items you wish to purchase into your shopping cart and try again.'
            );

            $this->redirect('/home');
        }
        catch(Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(
            array(
                'plugin' => 'payments',
                'controller' => 'shopping_carts',
                'action' => 'view',
            )
        );
    }

    /**
     * CCP credit card failure function
     *
     * @return void
     */
    public function credit_card_fail()
    {
        try
        {
            $this->Session->setFlash(
                'There was an error processing your transaction. If you wish to complete your purchase in the
                future you can add the items you wish to purchase into your shopping cart and try again.'
            );

            $this->redirect('/home');
        }
        catch(Exception $e)
        {
            $this->Session->setFlash($e->getMessage());
        }

        $this->redirect(
            array(
                'plugin' => 'payments',
                'controller' => 'shopping_carts',
                'action' => 'view',
            )
        );
    }
}