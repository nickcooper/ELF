<div id="personal_information" class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <th width="120" scope="row">Name</th>
                <td><?php echo $account['label']; ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">Account Email</th>
                <td><?php echo $account['email']; ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">A&amp;A Account</th>
                <td><?php echo $account['username']; ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">SSN</th>
                <td><?php echo sprintf('***-**-%s', $account['ssn_last_four']); ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">Date of Birth</th>
                <td><?php echo GenLib::dateFormat($account['dob']); ?></td>
            </tr>
        </tbody></table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php
                echo $this->Html->aclLink(
                    'Edit Info',
                    array(
                        'plugin' => 'accounts',
                        'controller' => 'accounts',
                        'action' => 'edit',
                        $account['id'],
                        'return' => base64_encode($this->here),
                    ),
                    array(
                        'class' => 'button small',
                        'label_show' => false
                    )
                );
            ?>
        </div>
    </div>
</div>