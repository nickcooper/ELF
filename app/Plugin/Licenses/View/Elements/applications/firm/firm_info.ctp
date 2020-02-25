<?php if (GenLib::isData($firm, null, array('id'))) : ?>

<div class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="160">Name</th>
                    <td><?php echo $this->textProcessing->checkForBlank($firm['label']); ?></td>
                </tr>

                <?php if (GenLib::isData($firm, null, array('alias'))) : ?>
                <tr>
                    <th width="120">Doing Business As</th>
                    <td><?php echo $firm['alias']; ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th width="120">Firm Type</th>
                    <td><?php echo $this->textProcessing->checkForBlank($firm['FirmType']['label']);?></td>
                </tr>
                <tr>
                    <th width="120">Do Not Send Mail</th>
                    <td><?php echo ($firm['no_mail'] ? '&#10004;' : ''); ?></td>
                </tr>
                <tr>
                    <th width="120">No Public Contact</th>
                    <td><?php echo ($firm['no_public_contact'] ? '&#10004;' : ''); ?></td>
                </tr>
                <tr>
                    <th width="120">Person Responsible (<acronym title="Person Responsible">PR</acronym>)</th>
                    <td>
                        <?php
                            if (GenLib::isData($contact, null, array('label'))) :
                                echo $contact['label'];
                            elseif ($app_open) :
                                echo $this->Html->link(
                                    'Add Information',
                                    array(
                                        'plugin' => 'firms',
                                        'controller' => 'firms',
                                        'action' => 'edit',
                                        $firm['id'],
                                        'return' => $return,
                                    ),
                                    array(
                                        'class' => 'blank'
                                    )
                                );
                            endif;
                        ?>
                    </td>
                </tr>

                <?php if (GenLib::isData($contact, null, array('label'))) : ?>
                <tr>
                    <th width="120"><acronym title="Person Responsible">PR</acronym> Phone</th>
                    <td><?php echo $this->textProcessing->checkForBlank(GenLib::phoneNumber($contact['phone'])); ?></td>
                </tr>
                <tr>
                    <th width="120"><acronym title="Person Responsible">PR</acronym> Email</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contact['email']); ?></td>
                </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                'Edit Information',
                array(
                    'plugin' => 'firms',
                    'controller' => 'firms',
                    'action' => 'edit',
                    $firm['id'],
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
        </div>
    </div>
</div>

<?php endif; ?>