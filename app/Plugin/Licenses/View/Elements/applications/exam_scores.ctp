<div id="exam_scores" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>
    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
               { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($exam_scores, '0', array('id'))) : ?>
        <div class="actions">
            <?php
                echo $this->Html->link(
                    __('Add Exam Score'),
                    array(
                        'plugin'     => 'licenses',
                        'controller' => 'exam_scores',
                        'action'     => 'add',
                        'fp'         => 'Licenses',
                        'fo'         => 'Application',
                        'fk'         => $application_id,
                        'return'     => $return,
                    ),
                    array('class' => 'button small')
                );
            ?>
        </div>
        <table class="light_data" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th scope="row">Exam Date</th>
                    <th scope="row">Score</th>
                    <th scope="row">State Sponsored?</th>
                    <th scope="row"><?php echo __('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exam_scores as $exam_score): ?>

                <tr>
                    <td>
                        <?php
                            // edit
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $exam_score['exam_date'],
                                    array(
                                        'plugin'     => 'licenses',
                                        'controller' => 'exam_scores',
                                        'action'     => 'edit',
                                        $exam_score['id'],
                                        'fp'         => 'Licenses',
                                        'fo'         => 'Application',
                                        'fk'         => $application_id,
                                        'return'     => $return,
                                    ),
                                    array(
                                        'title' => sprintf(__('Edit %s', $exam_score['exam_date']))
                                    )
                                );
                            }
                            else
                            {
                                echo $exam_score['exam_date'];
                            }
                        ?>
                    </td>
                    <td><?php echo $exam_score['score']; ?></td>
                    <td><?php echo $exam_score['sponsored']; ?></td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {
                                // allow removal
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i> ' . __('Remove'),
                                    array(
                                        'plugin'     => 'licenses',
                                        'controller' => 'exam_scores',
                                        'action'     => 'delete',
                                        $exam_score['id'],
                                        'return'     => $return,
                                    ),
                                    array(
                                        'title' => 'Remove Exam',
                                        'class' => 'inline_action warning',
                                        'escape' => false
                                    ),
                                    __('Are you sure you want to remove exam %s?', $exam_score['exam_date']
                                    )
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
            echo $this->Html->link(
                __('Add Exam Score'),
                array(
                    'plugin'     => 'licenses',
                    'controller' => 'exam_scores',
                    'action'     => 'add',
                    'fp'         => 'Licenses',
                    'fo'         => 'Application',
                    'fk'         => $application_id,
                    'return'     => $return,
                ),
                array('class' => 'button small')
            );
        ?>
    </div>

    <?php endif; ?>
</div>