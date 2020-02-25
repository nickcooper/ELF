<div id="education" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if(isset($description) && $description['element_plugin'] == 'accounts')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($educations, '0', array('id'))) : ?>
        <div class="actions">
            <?php
                echo $this->Html->link(
                    __('Add Education Information'),
                    array(
                        'plugin'     => 'accounts',
                        'controller' => 'educations',
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
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <tbody><tr>
                <th scope="row"><?php echo __('Type'); ?></th>
                <th scope="row"><?php echo __('Certified Date'); ?></th>
                <th scope="row"><?php echo __('Document'); ?></th>
                <th scope="row"><?php echo __('Highest Earned'); ?></th>
                <th><?php echo __('Actions'); ?></th>
            </tr>
            <?php foreach ($educations as $education): ?>
            <tr>
                <td>
                    <?php
                        // edit
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                $education['Degree']['degree'],
                                array(
                                    'plugin'     => 'accounts',
                                    'controller' => 'educations',
                                    'action'     => 'edit',
                                    $education['id'],
                                    'fp'         => 'Accounts',
                                    'fo'         => 'Account',
                                    'fk'         => $account['id'],
                                    'return'     => $return,
                                ),
                                array(
                                    'title' => sprintf(__('Edit %s', $education['Degree']['degree']))
                                )
                            );
                        }
                        else
                        {
                            echo $education['Degree']['degree'];
                        }
                    ?>
                </td>
                <td><?php echo $this->TextProcessing->checkForBlank(GenLib::dateFormat($education['certified_date'])); ?></td>
                <td>
                    <?php if (GenLib::isData($education, 'Upload.0', array('id'))): ?>
                        <?php foreach($education['Upload'] as $upload): ?>
                            <?php
                                echo $this->Html->link(
                                    '<i class="icon-file-alt"></i> ' . __('View'),
                                    DS.$upload['file_path'].DS.$upload['file_name'],
                                    array(
                                        'title' => __('View Document'),
                                        'target' => '_blank',
                                        'class' => 'inline_action',
                                        'escape' => false
                                    )
                                );
                            ?><br />
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $education['highest_earned'] ? '<i class="icon-star blue" title="Highest Earned Education"></i>' : ''; ?></td>
                <td width="85">
                    <?php
                        // allow removal
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                '<i class="icon-remove-sign"></i> ' . __('Remove'),
                                array(
                                    'plugin'     => 'accounts',
                                    'controller' => 'educations',
                                    'action'     => 'delete',
                                    $education['id'],
                                    'return'     => $return,
                                ),
                                array(
                                    'title' => 'Remove Education',
                                    'class' => 'inline_action warning',
                                    'escape' => false
                                ),
                                __('Are you sure you want to remove education %s?', $education['Degree']['degree']
                                )
                            );
                        }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody></table>

    <?php else : ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->link(
                __('Add Education Information'),
                array(
                    'plugin'     => 'accounts',
                    'controller' => 'educations',
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
