<div id="Firm" class="form_section">
    <h3><?php echo __('Firm Information');?> <span class="req"><?php echo __('Required Section'); ?></span></h3>

<?php if (GenLib::isData($firm, null, array('id'))): ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Firm Name'); ?></th>
                    <td><?php echo h($this->TextProcessing->checkForBlank($firm['label'])); ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('License Number'); ?></th>
                    <td><?php echo h($this->TextProcessing->checkForBlank($firm['License'][0]['license_number'])); ?></td>
                </tr>
            </tbody>
       </table>
    </div>
    <div class="span-5 last">

    <?php if ($isIncomplete): ?>

        <div class="actions">
        <?php
                echo $this->Html->link(
                    __('Change Associate Firm'),
                    array(
                        'plugin'     => 'abatements',
                        'controller' => 'abatements',
                        'action'     => 'associate_firm',
                        $abatement['id'],
                        $license['id'],
                        'return'     => base64_encode($this->here),
                    ),
                    array('class' => 'button small')
                );
        ?>

        </div>

    <?php else: ?>

            <div class="notice"><?php echo __('Abatement completed'); ?></div>

    <?php endif; ?>

    </div>
<?php else: ?>

    <div class="actions text_center">
        <?php
            if ($isIncomplete)
            {
                echo $this->Html->link(
                    __('Add Associate Firm'),
                    array(
                        'plugin'     => 'abatements',
                        'controller' => 'abatements',
                        'action'     => 'associate_firm',
                        $abatement['id'],
                        $license['id'],
                        'return'     => base64_encode($this->here),
                    ),
                    array('class' => 'button small')
                );
            }
        ?>
    </div>

<?php endif; ?>
</div>