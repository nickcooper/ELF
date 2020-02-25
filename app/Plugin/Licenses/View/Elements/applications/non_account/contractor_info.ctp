<div class="form_section">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php
    // check for data
    if (GenLib::isData($contractor, null, array('id'))) :
    ?>
    <div class="span-12">
        <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <th width="120" scope="row">Contractor Registration Number</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contractor['crn']); ?></td>
                </tr>
                <tr>
                    <th width="120" scope="row">CRN Expire Date</th>
                    <td><?php echo $this->textProcessing->checkForBlank($contractor['crn_expire_date'], array('type' => 'date')); ?></td>
                </tr>
                <tr>
                    <th width="120" scope="row">Federal ID Number</th>
                    <td>*****<?php echo $this->textProcessing->checkForBlank($contractor['fin_last_four'], array('before' => '*****')); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
        <?php
            echo $this->Html->link(
                sprintf('Edit %s', $label),
                array(
                    'plugin' => 'licenses',
                    'controller' => 'contractors',
                    'action' => 'edit',
                    $license['id'],
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
        </div>
    </div>

    <?php else : ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->link(
                sprintf('Edit %s', $label),
                array(
                    'plugin' => 'licenses',
                    'controller' => 'contractors',
                    'action' => 'add',
                    $license['id'],
                    'return' => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>