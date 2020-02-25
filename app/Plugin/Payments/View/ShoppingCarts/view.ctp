<div id="section">
    <div class="pad">
        <h2>Review Items</h2>
        <hr/>

        <p><a href="#" class="help_link"></a> Need to add additional licenses? Select <?php echo $this->Html->link('"My Account"', '/my_account'); ?> at the top right of this page.</p>

        <?php echo $this->element('payment_items', array('plugin' => 'Payments')); ?>

        <?php if($payment_items['final_total'] == 0): ?>
        <p><a href="#" class="help_link"></a> Zero dollar amount? Please continue to the next screen.</p>
        <?php endif; ?>

        <div class="actions textcenter">

            <?php
                // only display the continue button if there are items to pay for
                if (GenLib::isData($payment_items, 'PaymentItem.0', array('id')))
                {
                    echo $this->Html->link('Continue', array('plugin' => 'payments', 'controller' => 'payments', 'action' => 'add'), array('class' => 'button submit'));
                }
            ?>
            <?php echo $this->Html->link('Cancel', array('plugin' => 'licenses', 'controller' => 'licenses',
            'action' => 'index'), array('class' => 'button cancel')); ?>
        </div>
    </div>
</div>
