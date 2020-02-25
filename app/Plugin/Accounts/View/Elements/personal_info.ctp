<div id="personal_information" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <div class="span-4">
        <?php if (isset($account_photo) && $account_photo) : ?>
        <?php echo $this->Html->image($account_photo, array(
            'alt' => 'Photo of ' . $account['label'],
            'width' => '150',
            'height' => '200'
        )); ?>
        <?php else : ?>
        <?php echo $this->Html->image('photos/default-image.png', array(
            'alt' => 'There is no photo uploaded for ' . $account['label'],
            'width' => '150',
            'height' => '200'
        )); ?>
        <?php endif; ?>
    </div>
    <div class="span-8">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <th width="120" scope="row">Name</th>
                <td> <?php
                        if (isset($app_open) && $app_open)
                        {
                            echo $this->Html->link(
                                $this->textProcessing->checkForBlank($account['label']),
                                $fo_link
                            );
                        }
                        else
                        {
                            echo $this->textProcessing->checkForBlank($account['label']);
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <th width="120" scope="row">Account Email</th>
                <td><?php echo $this->textProcessing->checkForBlank($account['email']); ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">A&amp;A Account</th>
                <td><?php echo $this->textProcessing->checkForBlank($account['username']); ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">SSN</th>
                <td><?php
                    $ssn = ($account['ssn_last_four'] == '') ? '' : sprintf('***-**-%s', $account['ssn_last_four']);
                    echo $this->textProcessing->checkForBlank($ssn);
                ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">Date of Birth</th>
                <td><?php echo $this->textProcessing->checkForBlank(GenLib::dateFormat($account['dob'])); ?></td>
            </tr>
            <tr>
                <th width="120" scope="row">Do Not Send Mail</th>
                <td><?php echo ($account['no_mail'] ? '&#10004;' : ''); ?></td>
            </tr>
            <tr>
                <th width="120">No Public Contact</th>
                <td><?php echo ($account['no_public_contact'] ? '&#10004;' : ''); ?></td>
            </tr>
        </tbody></table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php
                echo $this->Html->AcLlink(
                    'Edit Info',
                    array(
                        'plugin' => 'accounts',
                        'controller' => 'accounts',
                        'action' => 'edit',
                        $account['id'],
                        'return' => $return,
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
