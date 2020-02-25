<div id="section">
    <div class="pad">
        <h2>Add A Payment</h2>
        <hr/>
        <?php echo $this->element('payment_items', array('plugin' => 'Payments')); ?>
        <p class='notice'>You will be leaving this site to make your payment. Upon doing this your items will be removed from your shopping cart, and submitted for payment. If you do not complete the payment process you will need to add items to your shopping cart again to complete your purchase.</p>
        <?php echo $this->Html->link(
            'Back to Shopping Cart',
            array(
                'plugin' => 'payments',
                'controller' => 'shopping_carts',
                'action' => 'view',
            ),
            array('class' => 'button')
        );?>

        <?php echo $this->Html->link(
            'Pay Now',
            array(
                'plugin' => 'payments',
                'controller' => 'shopping_carts',
                'action' => 'preprocess_cc',
            ),
            array('class' => 'button')
        );?>
    </div>
</div>
