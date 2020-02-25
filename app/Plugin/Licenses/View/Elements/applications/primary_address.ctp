<div id="Primary_Address" class="form_section">
    <h3><?php echo sprintf('%s Primary Address', $foreign_obj); ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    
    <?php if (GenLib::isData($license, sprintf('%s.PrimaryAddress', $foreign_obj), array('id'))) : ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="120">Address</th>
                    <td><?php echo $this->element('AddressBook.templates/standard_address', array('address' => $license[$foreign_obj]['PrimaryAddress'])); ?></td>
                </tr>
                <tr>
                    <th width="120">Phone</th>
                    <td><?php echo $this->element('AddressBook.templates/phone_and_fax', array('address' => $license[$foreign_obj]['PrimaryAddress'])); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php 
                echo $this->Html->link(
                    sprintf('%s Address Book', $humanized_foreign_obj), 
                    array(
                        'plugin' => 'address_book',
                        'controller' => 'addresses',
                        'action' => 'address_book', 
                        'fp' => $foreign_plugin,
                        'fo' => $foreign_obj,
                        'fk' => $foreign_key,
                        'return' => base64_encode($this->here)
                    ), 
                    array('class' => 'button small')
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
                    'return' => base64_encode($this->here)
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>