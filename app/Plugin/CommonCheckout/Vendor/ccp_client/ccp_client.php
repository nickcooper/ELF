<?php

/**
 * CCP Client
 *
 * @package CommonCheckout.Vendor
 * @author  Iowa Interactive, LLC.
 */
class CCP_Client
{
    // Configuration variables

    private $base_url = null;

    private $wsdl_url = null;

    private $form_url = null;

    private $success_url = null;

    private $failure_url = null;

    private $cancel_url = null;

    private $duplicate_url = null;

    private $state_code = null;

    private $merchant_id = null;

    private $merchant_key = null;

    private $service_code = null;

    // Working variables

    private $local_ref_id = null;

    private $transaction_id = null;

    private $description = null;

    private $total_amount = null;

    private $payment_items = array();

    private $token = null;

    private $payment_request = null;

    private $client = null;

    /**
     * __construct method
     *
     * @param array $config configuration array
     *
     * @return void
     * @access public
     */
    public function __construct($config = array())
    {
        try
        {
            $this->setConfig($config);

            $options = array(
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => false,
            );

            $this->client = new SoapClient($this->wsdl_url, $options);
        }
        catch (Exception $e)
        {

        }

    }

    /**
     * setConfig method
     *
     * @param array $config configuration array
     *
     * @return void
     * @access public
     */
    private function setConfig($config = array())
    {
        foreach ($config as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    /**
     * addPaymentItem method
     *
     * @param int    $item_id     item id
     * @param string $sku         sku string
     * @param string $description description
     * @param float  $unit_price  unit price
     * @param int    $quantity    quantity
     *
     * @return true
     * @access public
     */
    public function addPaymentItem($item_id = null, $sku = null, $description = null, $unit_price = null, $quantity = null)
    {
        // format the unit price
        $unit_price = sprintf("%01.2f", $unit_price);

        $payment_item = array(
            'ITEM_ID' => $item_id,
            'SKU' => $sku,
            'DESCRIPTION' => $description,
            'UNIT_PRICE' => $unit_price,
            'QUANTITY' => $quantity,
        );

        $this->payment_items[] = $payment_item;
        $this->total_amount = sprintf("%01.2f", $this->total_amount + $unit_price);

        return true;
    }

    /**
     * buildPaymentRequest method
     *
     * @return void
     * @access public
     */
    private function buildPaymentRequest()
    {
        $this->payment_request = array(
            'request' => array(
                'STATECD'        => $this->state_code,
                'MERCHANTID'     => $this->merchant_id,
                'MERCHANTKEY'    => $this->merchant_key,
                'SERVICECODE'    => $this->service_code,
                'AMOUNT'         => $this->total_amount,
                'LOCALREFID'     => $this->local_ref_id,
                'UNIQUETRANSID'  => $this->transaction_id,
                'DESCRIPTION'    => $this->description,
                'HREFSUCCESS'    => $this->success_url,
                'HREFFAILURE'    => $this->failure_url,
                'HREFCANCEL'     => $this->cancel_url,
                'HREFDUPLICATE'  => $this->duplicate_url,
                'LINEITEMS'      => array(
                    'LINEITEM' => $this->payment_items,
                ),
            )
        );
    }

    /**
     * generateToken method
     *
     * @return sting
     * @access public
     */
    public function generateToken()
    {
        try
        {
            $this->buildPaymentRequest();
            $response = $this->client->PreparePaymentv2($this->payment_request);

            // check for error
            if (!$response->PreparePaymentv2Result->TOKEN)
            {
                throw new Exception($response->PreparePaymentv2Result->ERRORMESSAGE);
            }

            return $this->token = $response->PreparePaymentv2Result->TOKEN;
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
            if (!$this->token)
            {
                $this->generateToken();
            }

            return sprintf('%s?token=%s', $this->form_url, $this->token);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * setTransactionId method
     *
     * @param int $trans_id transaction id
     *
     * @return void
     * @access public
     */
    public function setTransactionId($trans_id = null)
    {
        $this->transaction_id = $trans_id;
    }

    /**
     * setLocalRefId method
     *
     * @param string $local_ref_id local reference id
     *
     * @return void
     * @access public
     */
    public function setLocalRefId($local_ref_id = null)
    {
        $this->local_ref_id = $local_ref_id;
    }

    /**
     * getPaymentInfo method
     *
     * @param string $token token
     *
     * @return void
     * @access public
     */
    public function getPaymentInfo($token = null)
    {
        try
        {
            $obj = new StdClass();
            $obj->token = $token;
            return $this->client->GetPaymentInfo($obj);
        }
        catch (Exeption $e)
        {
            echo 'Request:'.PHP_EOL;
            echo $client->__getLastRequest().PHP_EOL;
            echo PHP_EOL;
            echo 'Response:'.PHP_EOL;
            echo $client->__getLastResponse().PHP_EOL;
            echo PHP_EOL;
            echo $e->getMessage().PHP_EOL;
        }
    }
}