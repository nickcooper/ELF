<div id="Firm" class="form_section <?php echo $open_close_class;?>">
    <h3>Firm Associations<?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($firm_licenses, '0', array('id'))) : ?>
    <div class="actions">
    <?php
        echo $this->Html->link(
            'Add Associated Firm',
            array(
                'plugin' => 'firms',
                'controller' => 'firms',
                'action' => 'find_firm_for_license',
                $license['id'],
                'return' => base64_encode($this->here)
            ),
            array('class' => 'button small')
        );
    ?>
    </div>
    <table class="light_data" border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th>License #</th>
                <th>Name</th>
                <th>Status</th>
                <th>Expiration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($firm_licenses as $firm_license): ?>
            <tr>
                <td>
                    <?php
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                ($firm_license['Firm']['License']['license_number'] ? $firm_license['Firm']['License']['license_number'] : 'Incomplete'),
                                array(
                                    'plugin' => 'licenses',
                                    'controller' => 'licenses',
                                    'action' => 'view',
                                    $firm_license['Firm']['License']['id'],
                                    'return' => base64_encode($this->here)
                                )
                            );
                        }
                        else
                        {
                            echo ($firm_license['Firm']['License']['license_number'] ? $firm_license['Firm']['License']['license_number'] : 'Incomplete');
                        }
                    ?>
                </td>
                <td><?php echo $firm_license['Firm']['alias'] ? $firm_license['Firm']['alias'] : $firm_license['Firm']['label']; ?></td>
                <td><?php echo $firm_license['Firm']['License']['LicenseStatus']['status']; ?></td>
                <td><?php echo GenLib::dateFormat($firm_license['Firm']['License']['expire_date']); ?></td>
                <td width="85">
                    <?php
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                '<i class="icon-remove-sign"></i> Remove',
                                array(
                                    'plugin' => 'firms',
                                    'controller' => 'firms',
                                    'action' => 'remove_associated_license',
                                    $license['id'],
                                    $firm_license['Firm']['id'],
                                    'return' => base64_encode($this->here)
                                ),
                                array(
                                    'class' => 'inline_action warning',
                                    'escape' => false
                                ),
                                __('Are you sure you want to delete firm association #%s?', $firm_license['Firm']['id']),
                                array('title' => 'Remove Experience')
                            );
                        }
                    ?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>

    <?php else : ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->link(
                'Add Associated Firm',
                array(
                    'plugin' => 'firms',
                    'controller' => 'firms',
                    'action' => 'find_firm_for_license',
                    $license['id'],
                    'return' => base64_encode($this->here)
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>
