<?php
    $add_link = $this->Html->link(
        'Add Other Licenses', 
        array(
            'plugin' => 'accounts', 
            'controller' => 'other_licenses', 
            'action' => 'add',
            'fp' => 'Accounts',
            'fo' => 'Account',
            'fk' => $account['id'],
            'return' => $return,
        ), 
        array('class' => 'button small')
    );
?>

<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    
    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($other_licenses, '0', array('id'))): ?>
    
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">License</th>
                    <th scope="col">License Number</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($other_licenses as $other_license) : ?>
                <tr>
                    <td>
                        <?php 
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $other_license['label'],
                                    array(
                                        'plugin' => 'accounts', 
                                        'controller' => 'other_licenses', 
                                        'action' => 'edit', 
                                        $other_license['id'],
                                        'fp' => 'Accounts',
                                        'fo' => 'Account',
                                        'fk' => $account['id'],
                                        'return' => $return,
                                    )
                                ); 
                            }
                            else
                            {
                                echo $other_license['label'];
                            }
                        ?>
                    </td>
                    <td><?php echo $other_license['license_number']; ?></td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {
                                    echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i>&nbsp;Remove', 
                                    array(
                                        'plugin' => 'accounts', 
                                        'controller' => 'other_licenses', 
                                        'action' => 'delete', 
                                        $other_license['id'],
                                        'return' => $return,
                                    ), 
                                    array(
                                        'class' => 'inline_action warning',
                                        'title' => 'Remove document',
                                        'escape' => false
                                    ), 
                                    __('Are you sure you want to delete other license #%s?', $other_license['id']), array('title' => 'Remove other license')
                                );
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php echo $add_link; ?>
        </div>
    </div>

    <?php else: ?>

    <div class="actions text_center">
            <?php echo $add_link; ?>
    </div>

    <?php endif; ?>
</div>
