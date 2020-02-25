<?php
/**
* This is the Shopping Cart Controller
*
* @package Payments.Controller
* @author  Iowa Interactive, LLC.
*/
class ShoppingCartsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'ShoppingCarts';

    /**
     * Autoload models
     *
     * @var array
     * @access public
     */
    public $uses = array('Payments.ShoppingCart', 'Payments.Payment', 'Accounts.Group');

    /**
     * @var array
     * @access public
     */
    public $helpers = array('Number');

    /**
     * Autoload components
     *
     * @var array
     * @access public
     */
    public $components = array('CommonCheckout.CommonCheckout');

    /**
     * view method
     *
     * View shopping cart items list
     *
     * @return void
     */
    public function view ()
    {
        try
        {
            // get auth user id
            if (!$auth_user_id = $this->Auth->user('id'))
            {
                // no - throw an exception
                throw new Exception('Unauthorized user.');
            }

            // build the shopping cart
            $this->set('payment_items', $this->ShoppingCart->buildCart($auth_user_id));
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * removeFromShoppingCart method
     *
     * @param int $id The ShoppingCart id
     *
     * @return boolean true
     */
    public function delete ($id = null)
    {
        $this->set('shoppingCarts', $this->ShoppingCart->getList());
        try
        {
            if ($this->ShoppingCart->removeItem($id))
            {
                $this->Session->setFlash(__('The License was removed from the cart.', true));
                $this->redirect(array('plugin' => 'payments', 'controller' =>  'shopping_carts', 'action' =>'view'));
            }
        }
        catch (Exception $e)
        {
            // fail
            $this->Session->setFlash($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Sets up pending payment, connects to CCP to initiate payment, and redirects user to CCP
     *
     * @return void
     */
    public function preprocess_cc()
    {
        try
        {
            $account_id = $this->Auth->user('id');

            // Get credit cart payment type id
            $this->PaymentType = ClassRegistry::init('PaymentType');

            $cc_id = $this->PaymentType->field(
                'id',
                array('label' => 'Credit Card')
            );

            // convert the shopping cart to pending payments
            $pending_payment_id = $this->ShoppingCart->createPendingPaymentForUser($account_id, $cc_id);

            $this->PendingPayment = ClassRegistry::init('Payments.PendingPayment');
            $pending_payment = $this->PendingPayment->find(
                'first',
                array(
                    'conditions' => array('PendingPayment.id' => $pending_payment_id),
                    'contain' => array('PendingPaymentItem' => array('Modifier'))
                )
            );

            // Get the shopping cart and send items to ccp
            $item_count = 0;
            foreach ($pending_payment['PendingPaymentItem'] as $index => $item)
            {
                // add modifiers fees to the item fee
                foreach ($item['Modifier'] as $modifier)
                {
                    $item['fee'] += $modifier['fee'];
                }

                $fee_data = unserialize($item['fee_data']);
                $sku = $fee_data['Fee']['label'];

                // add item
                if (!$this->CommonCheckout->addPaymentItem(
                    $item_count++,
                    $sku,
                    $item['label'],
                    $item['fee'],
                    1 //quantity
                ))
                {
                    throw new Exception('Failed to add payment item to common checkout client.');
                }
            }

            // set the local_ref_id and trans_id for common checkout client
            $local_ref_id = $pending_payment['PendingPayment']['local_transaction_id'];
            $this->CommonCheckout->setLocalRefId($local_ref_id);
            $this->CommonCheckout->setTransactionId(base64_encode($local_ref_id));

            // generate the common checkout url
            $common_checkout_url = $this->CommonCheckout->generateUrl();

            // redirect
            $this->redirect($common_checkout_url);
        }
        catch (Exception $e)
        {
            $this->Session->setFlash('There was an error processing your shopping cart for payment');
            $this->redirect(
                array(
                    'plugin' => 'payments',
                    'controller' => 'shopping_carts',
                    'action' => 'view',
                )
            );
        }
    }
}