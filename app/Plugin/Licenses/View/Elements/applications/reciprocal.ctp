<div id="reciprocal" class="form_section <?php echo $open_close_class;?>">
    <h3>Reciprocal Education<?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
        { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($reciprocals, '0', array('id'))) : ?>

    <div class="actions">
    <?php
        echo $this->Html->appLink(
            'Add Reciprocal Courses',
            array(
                'plugin' => 'licenses',
                'controller' => 'reciprocals',
                'action' => 'add',
                'fp' => 'Licenses',
                'fo' => 'Application',
                'fk' => $application['id'],
                'account' => $account_id,
                'return' => $return,
            ),
            array('class' => 'button small'),
            false,
            $app_open
        );
    ?>
    </div>

    <table class="light_data" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <th>Course</th>
                <th>Hours</th>
                <th>Complete</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($reciprocals as $reciprocal): ?>
                <tr>
                    <td width="120">
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $reciprocal['course_title'],
                                    array(
                                        'plugin' => 'licenses',
                                        'controller' => 'reciprocals',
                                        'action' => 'edit',
                                        $reciprocal['id'],
                                        'fp' => 'Licenses',
                                        'fo' => 'Application',
                                        'fk' => $application['id'],
                                        'account' => $account_id,
                                        'return' => $return,
                                    )
                                );
                            }
                            else
                            {
                                echo $reciprocal['course_title'];
                            }
                            
                        ?>
                    </td>
                    <td>
                        <?php echo h($reciprocal['hours']); ?>
                    </td>
                    <td>
                        <?php echo h(($reciprocal['pass'] ? 'Pass' : 'Fail')); ?>
                    </td>
                    <td>
                        <?php echo GenLib::dateFormat($reciprocal['completed_date']); ?>
                    </td>
                    <td>
                        <?php
                            if ($app_open)
                            {
                                
                                // allow removal
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i> ' . __('Remove'),
                                    array(
                                        'plugin'     => 'licenses',
                                        'controller' => 'reciprocals',
                                        'action'     => 'delete',
                                        $reciprocal['id'],
                                        'return'     => $return,
                                    ),
                                    array(
                                        'title' => 'Remove Reciprocal Course',
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ),
                                    __('Are you sure you want to remove reciprocal course %s?', $reciprocal['course_title'])
                                );
                            }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php else : ?>

    <div class="actions text_center">
        <?php
            echo $this->Html->appLink(
                'Add Reciprocal Courses',
                array(
                    'plugin' => 'licenses',
                    'controller' => 'reciprocals',
                    'action' => 'add',
                    'fp' => 'Licenses',
                    'fo' => 'Application',
                    'fk' => $application['id'],
                    'account' => $account_id,
                    'return' => $return,
                ),
                array('class' => 'button small'),
                false,
                $app_open
            );
        ?>
    </div>

    <?php endif; ?>
</div>