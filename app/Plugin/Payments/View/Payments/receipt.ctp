<div id="section">
    <div class="pad">
        <h2><?php echo __('Receipt'); ?></h2>
        <hr />
        <h3><?php echo __('Transaction Information'); ?></h3>

        <table border="0" cellpadding="0" cellspacing="0" class="light_data span-8">
            <tbody>
                <tr>
                    <td>Transaction ID:</td>
                    <td><?php echo $payment['Payment']['local_transaction_id']; ?></td>
                </tr>
            <?php if (!empty($payment['Payment']['transaction_id'])): ?>
                <tr>
                    <td>TPE Transaction ID:</td>
                    <td><?php echo $payment['Payment']['transaction_id']; ?></td>
                </tr>
            <?php endif; ?>
                <tr>
                    <td>Payment Date:</td>
                    <td><?php echo GenLib::dateFormat($payment['Payment']['created'], (substr($payment['Payment']['created'], -8) == '00:00:00') ? 'M j, Y' : 'M j,Y - H:i:s'); ?></td>
                </tr>
            </tbody>
        </table>

       <?php echo $this->element('payment_items', array('plugin' => 'Payments')); ?>

        <div class="actions text_center noprint">
            <?php
                echo $this->Html->link(
                    'Print Receipt',
                    '#',
                    array('class' => 'button',
                        'onClick' => 'window.print()',
                        'escape' => false)
                );
            ?>
            <?php
                echo $this->Html->link(
                    __('Continue'),
                    '/home',
                    array('class' => 'button')
                );
            ?>
        </div>
    </div>
</div>

