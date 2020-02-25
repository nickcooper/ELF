<div class="form_section">
    <h3><?php echo isset($label) ? $label : __('Licenses I Manage'); ?></h3>

    <p>In this section, you will have the ability to view, edit information and/or renew licenses that you are the manager for.</p>
    
    <?php if (GenLib::isData($managed_licenses, '0.License', array('id'))): ?>
        
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
            <?php 
                foreach($managed_licenses as $license) :
            ?>
            <tr>
                <td>
                    <?php
/*
                        if ($license['License']['pending'] == 1)
                        {
                            debug('Andrew, for determining the licenses that are pending, $license[License][pending] should = 1'); 
                        }
*/
                    ?>
                    
                    <?php
                        echo $this->Html->link(
                            ($license['License']['license_number'] ? $license['License']['license_number'] : 'Incomplete'),
                            array(
                                'plugin' => 'licenses',
                                'controller' => 'licenses',
                                'action' => 'view',
                                $license['License']['id'],
                                'return' => base64_encode($this->here)
                            ),
                            array('title' => 'View experience details')
                        );
                    ?>
                </td>
                <td><?php echo $license['License']['legacy_number']; ?></td>
                <td><?php echo $license['License']['LicenseType']['abbr']; ?></td>
                <td><?php echo $license['License']['LicenseStatus']['status']; ?></td>
                <td><?php echo GenLib::dateFormat($license['License']['expire_date']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>
