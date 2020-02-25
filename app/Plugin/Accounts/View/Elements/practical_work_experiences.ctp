<div class="form_section <?php echo $open_close_class;?>">
    <h3>
        <?php echo h($label); ?>
        <?php if ($required): ?>
        <span class="req"><?php echo __('Required Section'); ?></span>
        <?php endif; ?>
    </h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($practical_work_experiences, '0', array('id'))): ?>
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col"><?php echo __('Name'); ?></th>
                    <th scope="col"><?php echo __('Months'); ?></th>
                    <th scope="col" width="85"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($practical_work_experiences as $practical_work_experience): ?>
                <tr>
                    <td>
                        <?php
                            if ($app_open)
                            {  
                                echo $this->Html->link(
                                    $practical_work_experience['PracticalWorkExperienceType']['label'],
                                    array(
                                        'plugin'     => 'accounts',
                                        'controller' => 'practical_work_experiences',
                                        'action'     => 'edit',
                                        $practical_work_experience['id'],
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
                                echo $practical_work_experience['PracticalWorkExperienceType']['label'];
                            }
                        ?>
                    </td>
                    <td><?php echo $practical_work_experience['months']; ?></td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {    
                                echo $this->Html->link(
                                    sprintf('<i class="icon-remove-sign"></i> %s', __('Remove')),
                                    array(
                                        'plugin'     => 'accounts',
                                        'controller' => 'practical_work_experiences',
                                        'action'     => 'delete',
                                        $practical_work_experience['id'],
                                        'return'     => $return,
                                    ),
                                    array(
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ),
                                    sprintf(__('Are you sure you want to delete %s #%s?', strtolower($label), $practical_work_experience['id'])),
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
                    __('Add Work Experience'),
                    array(
                        'plugin'     => 'accounts',
                        'controller' => 'practical_work_experiences',
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
                    'controller' => 'practical_work_experiences',
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
