<div id="addresses" class="form_section">
    <h3><?php echo sprintf('%s Addresses', $humanized_foreign_obj); ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($addresses, '0', array('id'))) : ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <?php foreach ($addresses as $address): ?>
                <tr>
                    <th width="120">
                        <?php echo h($address['label']); ?>
                    </th>
                    <td>
                        <?php echo $this->element('AddressBook.templates/standard_address', array('address' => $address)); ?>
                    </td>
                    <td width="50">
                        <?php
                            if ($address['primary_flag']) :
                                echo '(primary)';
                            endif;
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php 
                echo $this->Html->link(
                    'Edit Addresses',
                    array(
                        'plugin' => 'address_book',
                        'controller' => 'addresses',
                        'action' => 'address_book', 
                        'fp' => $foreign_plugin,
                        'fo' => $foreign_obj,
                        'fk' => $foreign_key,
                        'return' => $return,
                    ), 
                    array(
                        'title' => sprintf('%s Address Book', $humanized_foreign_obj),
                        'class' => 'button small'
                    )
                ); 
            ?>
        </div>
    </div>
    
    <?php else : ?>
        
    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add Address', 
                array(
                    'plugin' => 'address_book',
                    'controller' => 'addresses',
                    'action' => 'add',
                    'fp' => $foreign_plugin,
                    'fo' => $foreign_obj,
                    'fk' => $foreign_key,
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>

</div>