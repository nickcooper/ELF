<?php
App::uses('Component', 'Controller');

/**
 * CommonCheckoutComponent Component
 *
 * @package CommonCheckout.Controller.Component
 * @author  Iowa Interactive, LLC.
 */
class CommonCheckoutComponent extends Component
{
    /**
     * initialize method
     *
     * @param obj $controller host controller, cake handles this
     *
     * @return void
     * @access public
     */
    public function initialize(Controller $controller)
    {
        // set our configuration
        $config = Configure::read('common_checkout_config');

        // load the vender library
        App::import('Vendor', 'CommonCheckout.ccp_client/ccp_client');
        $this->CCP_Client = new CCP_Client($config);
    }

    /**
     * addPaymentItem method
     *
     * @param int    $item_id     item id
     * @param string $sku         sku
     * @param string $description description
     * @param float  $unit_price  unit_price
     * @param int    $quantity    quantity
     *
     * @return true
     * @access public
     */
    public function addPaymentItem($item_id = null, $sku = null, $description = null, $unit_price = null, $quantity = null)
    {
        try
        {
            return $this->CCP_Client->addPaymentItem($item_id, $sku, $description, $unit_price, $quantity);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * generateUrl method
     *
     * @return string
     * @access public
     */
    public function generateUrl()
    {
        try
        {
            return $this->CCP_Client->generateUrl();
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * setTransactionId method
     *
     * @param sting $trans_id transaction id
     *
     * @return void
     * @access public
     */
    public function setTransactionId($trans_id = null)
    {
        $this->CCP_Client->setTransactionId($trans_id);
    }

    /**
     * setLocalRefId method
     *
     * @param sting $local_ref_id local reference id
     *
     * @return void
     * @access public
     */
    public function setLocalRefId($local_ref_id = null)
    {
        $this->CCP_Client->setLocalRefId($local_ref_id);
    }

    /**
     * getPaymentInfo method
     *
     * @param sting $token token string
     *
     * @return string
     * @access public
     */
    public function getPaymentInfo($token = null)
    {
        return $this->CCP_Client->getPaymentInfo($token);
    }
}