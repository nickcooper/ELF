<?php
/**
 * Payable Behavior.
 *
 * @package App.Model.Behavior
 * @author  Iowa Interactive, LLC.
 */
class PayableBehavior extends ModelBehavior
{
    /**
     * behavior setup
     * Behaviors are shared across all model instances.
     * It is good practice to to store setting per alias/model name.
     *
     * @param array $Model  Will check the model being passsed.
     * @param array $config Defined in the actAs array of the model loading this behavior
     *
     * @return void
     */
    public function setup(Model $Model, $config = array())
    {
        //default settings
        if (!isset($this->settings[$Model->alias]))
        {
            $this->settings[$Model->alias] = array(
                'contain' => array(),
            );
        }

        // This overwrites the defualt seetings with the settings being passed
        $this->settings[$Model->alias] = array_merge(
            $this->settings[$Model->alias],
            (array) $config
        );
        return true;
    }

    /**
     * removeFromShoppingCart method
     *
     * @param int $id Passing the shoppingcart $id.
     *
     * @return The obj of the deleted item in the shopping cart.
     */
    public function removeFromShoppingCart ($id)
    {
        App::Import('Payments.ShoppingCart');

        // Instantiating the ShoppingCart
        $this->ShoppingCart = new ShoppingCart();
        return $this->ShoppingCart->deleteCart($id);
    }

    /**
    * This the start up method.
    *
    * @param str &$controller Checking that the controller is load at start up.
    *
    * @return void
    */
    public function startup(&$controller)
    {
        $this->controller = $controller;
    }

    /**
     * getFeeByKey method
     *
     * @param arrray &$Model  Passing the model to tie to item.
     * @param str    $fee_key string value key to look up fee
     *
     * @return array returns matched fee record
     * @access public
     */
    public function getFeeByKey(&$Model, $fee_key = null)
    {
        $this->Fee = ClassRegistry::init('Payments.Fee');
        return $this->Fee->getFeeByKey($fee_key);

    }

    /**
     * addItem method
     *
     * @param str &$Model         the model of the plugin being passed
     * @param str $account_id     expecting the $account_id
     * @param str $foreign_plugin expecting the $foreign_plugin object plugin name
     * @param str $foreign_obj    expecting the foreign object model name
     * @param int $foreign_key    expecting the foreign object record id
     * @param int $fee            expecting the fee data
     * @param int $label          expecting the payable item label
     * @param int $owner          expecting the payable item owner
     *
     * @return void
     * @access public
     */
    public function addItem (&$Model, $account_id = null, $foreign_plugin = null, $foreign_obj = null, $foreign_key = null, $fee = null, $label = null, $owner = null)
    {
        App::uses('CakeSession', 'Model/Datasource');

        try
        {
            // load the ShoppingCart model
            $this->ShoppingCart = ClassRegistry::init('Payments.ShoppingCart');

            // add the fee to the shopping cart
            $this->ShoppingCart->addItem(
                $account_id,
                $foreign_plugin,
                $foreign_obj,
                $foreign_key,
                (isset($fee['Fee']['id'])) ? $fee['Fee']['id'] : null,
                $label,
                $owner
            );
        }
        catch (Exception $e)
        {
            throw new Exception(
                sprintf('Failed to add fee to shopping cart - %s.', $e->getMessage())
            );
        }
    }
}
