<table cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th scope="col">Item / License</th>
            <th scope="col"></th>
            <th scope="col" style="text-align:left;width:100px;">Fee</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($payment_items['PaymentItem'] as $item): ?>
        <tr>
            <td>
                <span style="font-size:12pt;">
                    <?php echo $item['label']; ?>
                    <?php if ($item['owner'] !== null): ?>
                        <?php echo sprintf(' for %s', $item['owner']); ?>
                    <?php endif; ?>
                </span>
                <br />
                <?php echo $item['descr']; ?>
            </td>
            <td>
                <?php
                    if ($item['removable']) :
                        echo $this->Html->link(
                            'Remove',
                            array(
                                'plugin' => 'payments',
                                'controller' => 'shopping_carts',
                                'action' => 'delete',
                                $item['id']
                            )
                        );
                    else:
                        echo ' ';
                    endif;
                 ?>
            </td>
            <td style="text-align:left;"><?php echo $this->Number->currency($item['fee']); ?></td>
        </tr>
        <?php foreach ($item['Modifier'] as $modifier): ?>
            <tr>
                <td colspan="2" style="padding-left:40px"><?php echo $modifier['label']; ?></td>
                <td style="text-align:left;"><?php echo $this->Number->currency($modifier['fee'], 'USD', array('negative' => '-')); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php if($payment_items['tax_total'] != 0): ?>
        <tr>
            <td colspan="2" class="alignright" style="text-align:right;">Sub Total:</td>
            <td style="text-align:left;"><?php echo $this->Number->currency($payment_items['sub_total'], 'USD');?></td>
        </tr>
        <tr>
            <td colspan="2" class="alignright" style="text-align:right;">Tax:</td>
            <td style="text-align:left;"><?php echo $this->Number->currency($payment_items['tax_total'], 'USD', array('after' => false));?></td>
        </tr>
    <?php endif; ?>
        <tr>
            <td colspan="2" class="alignright" style="text-align:right;font-size:12pt;">Total:</td>
            <td style="font-size:12pt;text-align:left;"><?php echo $this->Number->currency($payment_items['final_total'], 'USD');?></td>
        </tr>
    <?php if(isset($payment)) : ?>
        <tr>
            <td colspan="2" class="alignright" style="text-align:right;font-size:12pt;">Amount Paid:</td>
            <td style="font-size:12pt;text-align:left;"><?php echo $this->Number->currency($payment['Payment']['amount_paid'], 'USD');?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>