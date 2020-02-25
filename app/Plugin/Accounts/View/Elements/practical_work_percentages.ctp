<div class="form_section <?php echo $open_close_class;?>">
    <h3>
        <?php echo h($label); ?>
        <?php if ($required): ?>
        <span class="req"><?php echo __('Required Section'); ?></span>
        <?php endif; ?>
    </h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($percentages, '0', array('id'))): ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col"><?php echo __('Name'); ?></th>
                    <th scope="col"><?php echo __('Percentage'); ?></th>
                    <th scope="col" width="85"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($percentages as $percentage): ?>
                <tr>
                    <td>
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $percentage['PracticalWorkPercentageType']['label'],
                                    array(
                                        'plugin'     => 'accounts',
                                        'controller' => 'practical_work_percentages',
                                        'action'     => 'edit',
                                        $percentage['id'],
                                        'fp'         => 'Accounts',
                                        'fo'         => 'Account',
                                        'fk'         => $account['id'],
                                        'return'     => $return,
                                    ),
                                    array('title' => sprintf(__('View %s details'), strtolower($label)))
                                );
                            }
                            else
                            {
                                echo $percentage['PracticalWorkPercentageType']['label'];
                            }
                        ?>
                    </td>
                    <td><?php echo h(sprintf('%d%%', $percentage['percentage'])); ?></td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    sprintf('<i class="icon-remove-sign"></i> %s', __('Remove')),
                                    array(
                                        'plugin'     => 'accounts',
                                        'controller' => 'practical_work_percentages',
                                        'action'     => 'delete',
                                        $percentage['id'],
                                        'return'     => $return,
                                    ),
                                    array(
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ),
                                    sprintf(__('Are you sure you want to delete %s #%s?', strtolower($label), $percentage['id'])),
                                    array('title' => sprintf(__('Remove %s'), $label))
                                );
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php
                echo $this->Html->link(
                    sprintf(__('Add %s'), $label),
                    array(
                        'plugin'     => 'accounts',
                        'controller' => 'practical_work_percentages',
                        'action'     => 'add',
                        'fp'         => 'Accounts',
                        'fo'         => 'Account',
                        'fk'         => $account['id'],
                        'return'     => $return,
                    ),
                    array('class' => 'button small')
                );
            ?>
        </div>
    </div>

    <?php else: ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->link(
                sprintf(__('Add %s'), $label),
                array(
                    'plugin'     => 'accounts',
                    'controller' => 'practical_work_percentages',
                    'action'     => 'add',
                    'fp'         => 'Accounts',
                    'fo'         => 'Account',
                    'fk'         => $account['id'],
                    'return'     => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>
