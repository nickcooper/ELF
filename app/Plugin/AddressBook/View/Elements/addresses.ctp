<div id="addresses" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo sprintf('Addresses'); ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php if(isset($description) && $description['element_plugin'] == 'address_book')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($addresses, '0', array('id'))) : ?>
    <div class="span-12">
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th scope="row">Type</th>
                    <th scope="row">Address</th>
                    <th scope="row">Primary</th>
                    <th>Actions</th>
                </tr>
            <?php foreach ($addresses as $address): ?>
                <tr>
                    <td width="120">
                        <?php
                            // edit
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $address['label'],
                                    array(
                                        'plugin' => 'address_book',
                                        'controller' => 'addresses',
                                        'action' => 'edit',
                                        $address['id'],
                                        'fp' => $foreign_plugin,
                                        'fo' => $foreign_obj,
                                        'fk' => $foreign_key,
                                        'return' => $return,
                                    ),
                                    array(
                                        'title' => 'Edit '.$address['label'],
                                        'class' => ''
                                    )
                                );
                            }
                            else
                            {  
                                echo $address['label'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php echo $this->element('AddressBook.templates/standard_address', array('address' => $address)); ?>
                    </td>
                    <td>
                        <?php
                            if($address['primary_flag']) :
                                echo '<i class="icon-star blue" title="Primary Address"></i>';
                            endif;
                        ?>
                    </td>
                    <td width="85">
                        <?php
                            // check if address is primary
                            if(!$address['primary_flag'] && $app_open)
                            {
                                // allow removal
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i> Remove',
                                    array(
                                        'plugin' => 'address_book',
                                        'controller' => 'addresses',
                                        'action' => 'delete',
                                        $address['id'],
                                        'return' => $return,
                                    ),
                                    array(
                                        'title' => 'Remove address',
                                        'class' => 'inline_action warning',
                                        'escape' => false),
                                        __('Are you sure you want to remove address %s?', $address['label']
                                    )
                                );
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5">
        <div class="actions">
        <?php
            echo $this->Html->link(
                'Add New Address',
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
