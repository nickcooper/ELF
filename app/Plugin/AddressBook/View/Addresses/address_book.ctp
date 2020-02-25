<div id="body" class="span-24">
    
    <?php echo $this->element('section_nav'); ?>
    
    <div id="section" class="span-19 last">
        <div class="pad">
            <h3><?php echo sprintf('%s Address Book', $humanized_foreign_obj); ?></h3>   
            <div class="actions">
                <?php echo $this->Html->link('Add New Address', array('plugin' => 'address_book', 'controller' => 'addresses',
'action' => 'add', 'fp' => $foreign_plugin, 'fo' => $foreign_obj, 'fk' => $foreign_key), array('class' => 'button'));?>
            </div>
            <?php echo $this->Form->create('Address'); ?>
            <fieldset>
                <legend>Addresses</legend>         
                
                <?php if (!empty($addresses)) :?>
                <table class="light_data" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th scope="col">Primary</th>
                            <th scope="col">Type</th>
                            <th scope="col">Address</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($addresses as $address) : ?>
                        <tr>
                            <td>
                                <?php
                                    echo $this->Form->radio(
                                        'primary_flag', 
                                        array(
                                            $address['Address']['id'] => ''
                                        ),
                                        array(
                                            'hiddenField' => false,
                                            'checked' => ($address['Address']['primary_flag'] ? 'checked' : ''),
                                        )
                                    );
                                ?>
                            </td>
                            <td>
                                <?php 
                                    // edit
                                    echo $this->Html->link(
                                        $address['Address']['label'], 
                                        array(
                                            'plugin' => 'address_book', 
                                            'controller' => 'addresses', 
                                            'action' => 'edit', 
                                            $address['Address']['id'],
                                            'fp' => $foreign_plugin,
                                            'fo' => $foreign_obj,
                                            'fk' => $foreign_key,
                                            'return' => base64_encode($this->here)
                                        ), 
                                        array(
                                            'title' => 'Edit address'
                                        )
                                    );
                                ?>
                            </td>
                            <td>
                                <?php echo $this->element('templates/standard_address', array('address' => $address['Address'])); ?>
                            </td>
                            <td>
                                <?php echo $this->element('templates/phone_and_fax', array('address' => $address['Address'])); ?>
                            </td>
                            <td>
                                <?php          
                                    // check if address is primary
                                    if(!$address['Address']['primary_flag'] && $app_open)
                                    {
                                        // allow removal
                                        echo $this->Html->link(
                                            '<span class="icon-remove-sign"></i> ' . __('Remove'), 
                                            array(
                                                'plugin' => 'address_book', 
                                                'controller' => 'addresses', 
                                                'action' => 'delete', 
                                                $address['Address']['id'],
                                                'return' => base64_encode($this->here)
                                            ), 
                                            array(
                                                'class' => 'inline_action warning', 
                                                'title' => 'Remove address',
                                                'escape' => false
                                            ),
                                            __('Are you sure you want to remove address %s?', $address['Address']['label']
                                            )
                                        );
                                    } 
                                ?>
                            </td>
                        </tr>
                        
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
                <?php else : ?>
                <div class="notice">There are no saved addresses.</div>
                <?php endif; ?>
            </fieldset>
            <?php echo $this->Form->end('Save Primary'); ?>
        </div>
    </div>
</div>