<div class='form_section'>
    <h3>License Information <a href="#" class="help_tag">?</a></h3>
    <table class="light_data" border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th scope="col">License Number</th>
                <th scope="col">License Type</th>
                <th scope="col">Status</th>
                <th scope="col">Date Received</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($licenses as $license): ?>
                <tr>
                    <td>
                        <?php echo $this->Html->link(
                            empty($license['license_number']) ? 'N/A' : $license['license_number'],
                            array(
                                'plugin' => 'licenses',
                                'controller' => 'licenses',
                                'action' => 'application',
                                $license['id'],
                                'return' => base64_encode($this->here)
                            )
                        ); ?>
                    </td>
                    <td><strong><?php echo $license['LicenseType']['label']; ?></strong></td>
                    <td><strong><?php echo $license['LicenseStatus']['status']; ?></strong></td>
                    <td><strong><?php echo $license['issued_date']; ?></strong></td>
                    <td><strong><?php echo $license['issued_date']; ?></strong></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
