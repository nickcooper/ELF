<div class="form_section">
    <h3><?php echo isset($label) ? $label : __('Account Applications'); ?></h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

<?php /* if (! isset($show_add_button)): ?>
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
<?php endif; */ ?>
    <?php
    # check if data has been saved to decide what to show
    if (GenLib::isData($applications, '0', array('id'))):
    ?>
    <table border="0" cellpadding="0" cellspacing="0" class="light_data">
        <thead>
            <tr>
                <th scope="col">License Number</th>
                <th scope="col">License Type</th>
                <th scope="col">Application Status</th>
                <th scope="col">Submitted/Paid Date</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach($applications as $application) : ?>
            <tr<?php echo ($application['pending']) ? ' class="highlight"' : '';?>>
                <td>
                    <?php
                        echo $this->Html->link(
                            ($application['license_number'] ? $application['license_number'] : 'Incomplete'),
                            array(
                                'plugin' => 'licenses',
                                'controller' => 'licenses',
                                'action' => 'view',
                                $application['id'],
                                'return' => base64_encode($this->here)
                            ),
                            array('title' => 'View experience details')
                        );
                    ?>
                </td>
                <td><?php echo $application['LicenseType']['abbr']; ?></td>
                <td><?php echo ($application['Application'][0]['ApplicationStatus']['label'] == 'Pending') ? '<em>Pending</em>' . $this->Html->link('help', '/help_items/pending.html', array('class' => 'help_tag')) : $application['Application'][0]['ApplicationStatus']['label']; ?></td>
                <td><?php echo GenLib::dateFormat($application['Application'][0]['submit_paid_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
//print('<pre>'); print_r($applications); print('</pre>');
    endif; ?>
</div>
