<div id="section">
    <div class="pad">
        <h2>Add A Payment</h2>
        <hr/>
        <?php echo $this->Form->create('Payment');?>
        <div class="span-7">
            <fieldset>
                <legend>Money Order Payment</legend>
                <?php echo $this->Form->input('Payment.total', array('label' => 'Total', 'value' => $total, 
                'class' => 'text span-3', 'readonly' => 'readonly'))?>
                <?php echo $this->Form->input('Payment.amount_paid', array('label' => 'Amount', 'value' => $total, 'class' => 'text span-3', 'readonly' => 'readonly', 'after' => '<label><input type="checkbox" name="full_amount" value="full_amount" checked="checked" class="full_balance_toggle"/> Full balance</label></div>')); ?>
                <?php
                    if($group_id != '1')
                    {
                        if(Configure::read('min_year'))
                        { $min_year = Configure::read('min_year'); } else { $min_year = '1950'; }

                        if(Configure::read('max_year'))
                        { $max_year = Configure::read('max_year'); } else { $max_year = '2025'; }

                        echo $this->Form->input('Payment.payment_date', array('label' => 'Payment Date', 'type' => 'date', 'class' => 'date', 'minYear' => $min_year, 'maxYear' => $max_year));

                        echo $this->Form->input('Payment.payment_received_date', array('label' => 'Payment Received Date', 'type' => 'date', 'class' => 'date', 'minYear' => $min_year, 'maxYear' => $max_year));
                    }
                ?>
                <?php echo $this->Form->input('Payment.identifier', array('label' => 'Money Order Number', 'class' => 'text span-7')); ?>
            </fieldset>
        </div>
        <?php echo $this->Form->end(array('label' => 'Save Payment', 'div' => array('class' => 'actions textcenter')));?>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $('.full_balance_toggle').each(function(){
        var t = $(this).parents('.form_item').find('input.text');
        var full_val = t.val();
        $(this).click(function(){
            if ($(this).is(':checked')) {
                t.val(full_val).attr('readonly', 'readonly');
            } else {
                t.removeAttr('readonly').focus();
            }
        });
    });
});
</script>

