<div id="associated_licenses" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($firm_licenses, '0', array('id'))) : ?>
    <div class="actions">
    <?php 
        echo $this->Html->link(
            'Add Associated License', 
                array(
                    'plugin' => 'firms',
                    'controller' => 'firms',
                    'action' => 'add_license_to_firm',
                    $firm['id'],
                    'return' => $return,
                ),
            array('class' => 'button small')
        );
    ?>
    </div>
    <table class="light_data" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <th scope="row">License</th>
                <th scope="row">Name</th>
                <th scope="row">Status</th>
                <th scope="row">Expiration</th>
                <th scope="row">Actions</th>
            </tr>
            <?php foreach($firm_licenses as $firm_license) : ?>
                <tr>
                    <td>
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $firm_license['License']['license_number'],
                                    array(
                                        'plugin' => 'licenses',
                                        'controller' => 'licenses',
                                        'action' => 'view',
                                        $firm_license['License']['id'],
                                        'return' => $return,
                                    )
                                );
                            }
                            else 
                            {
                                echo $firm_license['License']['license_number'];
                            }
                        ?>
                    </td>
                    <td><?php echo $firm_license['License']['label']; ?></td>
                    <td><?php echo $firm_license['LicenseStatus']['status']; ?></td>
                    <td><?php echo GenLib::dateFormat($firm_license['License']['expire_date']); ?></td><td width="85">
                    <?php 
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                '<i class="icon-remove-sign"></i> Remove', 
                                array(
                                    'plugin' => 'firms', 
                                    'controller' => 'firms', 
                                    'action' => 'remove_associated_license',
                                    $firm_license['License']['id'], 
                                    $firm['id'],
                                    'return' => $return,
                                ), 
                                array(
                                    'class' => 'inline_action warning',
                                    'title' => 'Remove associated license',
                                    'escape' => false
                                ),
                                __('Are you sure you want to delete license association #%s?', $firm_license['License']['license_number'])
                            ); 
                        }
                    ?>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php else : ?>
        
    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add Associated License', 
                    array(
                        'plugin' => 'firms',
                        'controller' => 'firms',
                        'action' => 'add_license_to_firm',
                        $firm['id'],
                        'return' => $return,
                    ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>