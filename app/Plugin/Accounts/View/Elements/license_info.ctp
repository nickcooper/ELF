<div class="form_section">
    <h3><?php echo isset($label) ? $label : __('Account Licenses'); ?></h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

<?php if (! isset($show_add_button)): ?>
    <div class="actions">
        <?php
            echo $this->element(
                'Licenses.searchable_add_new_button',
                array(
                    'license_types' => $license_types,
                    'account_id'    => $account['id'],
                )
            );
        ?>
    </div>
<?php endif; ?>
    <?php
    # check if data has been saved to decide what to show
    if (GenLib::isData($licenses, '0', array('id'))):
    ?>
    <table border="0" cellpadding="0" cellspacing="0" class="light_data">
        <thead>
            <tr>
                <th scope="col">License Number</th>
                <th scope="col">Old License Number</th>
                <th scope="col">License Type</th>
                <th scope="col">Status</th>
                <th scope="col">Expire Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($licenses as $license) : ?>
            <tr<?php echo ($license['pending']) ? ' class="highlight"' : '';?>>
                <td>
                    <?php
                        echo $this->Html->link(
                            ($license['license_number'] ? $license['license_number'] : 'Incomplete'),
                            array(
                                'plugin' => 'licenses',
                                'controller' => 'licenses',
                                'action' => 'view',
                                $license['id'],
                                'return' => base64_encode($this->here)
                            ),
                            array('title' => 'View experience details')
                        );
                    ?>
                </td>
                <td><?php echo $license['legacy_number']; ?></td>
                <td><?php echo $license['LicenseType']['abbr']; ?></td>
                <td><?php echo ($license['pending']) ? '<em>Pending</em>' . $this->Html->link('help', '/help_items/pending.html', array('class' => 'help_tag')) : $license['LicenseStatus']['status']; ?></td>
                <td><?php echo GenLib::dateFormat($license['expire_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>
