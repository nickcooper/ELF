<div id="Abatement" class="form_section">
    <h3>
        <?php echo h($label); ?>
        <?php if (isset($required) && $required): ?>
            <span class="req"><?php echo __('Required Section'); ?></span>
        <?php endif; ?>
    </h3>
<?php if (GenLib::isData($occupants, '0', array('id'))) : ?>
    <div class="span-12">
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th><?php echo __('Name'); ?></th>
                    <th><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($occupants as $occupant): ?>
                <tr>
                    <td>
                        <?php
                            if ($isIncomplete)
                            {
                                echo $this->Html->link(
                                    sprintf('%s %s', $occupant['first_name'], $occupant['last_name']),
                                    array(
                                        'controller' => 'occupants',
                                        'action'     => 'edit',
                                        $abatement['id'],
                                        $occupant['id'],
                                        'return'     => base64_encode($this->here),
                                    )
                                );
                            }
                            else
                            {
                                echo h(sprintf('%s %s', $occupant['first_name'], $occupant['last_name']));
                            }
                        ?>
                    </td>
                    <td width="85">
                        <?php
                            if ($isIncomplete)
                            {
                                echo $this->Html->link(
                                    __('Remove'),
                                    array(
                                        'controller' => 'occupants',
                                        'action'     => 'delete',
                                        $abatement['id'],
                                        $occupant['id'],
                                        'return'     => base64_encode($this->here),
                                    ),
                                    array(
                                        'class' => 'iconify warning',
                                        'title' => __('Remove Occupant'),
                                    ),
                                    sprintf(
                                        __('Are you sure you want to delete occupant "%s %s"?'),
                                        $occupant['first_name'],
                                        $occupant['last_name']
                                    )
                                );
                            }
                        ?>
                    </td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
    <?php if ($isIncomplete): ?>
        <div class="actions">
        <?php
                echo $this->Html->link(
                    __('Add Occupant'),
                    array(
                        'controller' => 'occupants',
                        'action'     => 'add',
                        $abatement['id'],
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

    <?php if ($isIncomplete): ?>

    <div class="actions text_center">
    <?php
        echo $this->Html->link(
            __('Add Occupant'),
            array(
                'controller' => 'occupants',
                'action'     => 'add',
                $abatement['id'],
                'return'     => base64_encode($this->here),
            ),
            array('class' => 'button small')
        );
    ?>
    </div>

    <?php else: ?>

    <div class="notice text_center"><?php echo __('Abatement completed'); ?></div>

    <?php endif; ?>

<?php endif; ?>

</div>
