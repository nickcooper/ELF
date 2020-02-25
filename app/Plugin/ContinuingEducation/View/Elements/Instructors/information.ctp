<div class='form_section'>
    <h3><?php echo __('Training Instructor Information'); ?></h3>
    <?php if (empty($instructor)): ?>
        <div class="actions text_center">
            <?php
            echo $this->Html->link(
                __('Add Instructor'),
                '/continuing_education/instructors/add/',
                array('class' => 'button small')
            );
            ?>
        </div>
    <?php else: ?>
        <div class="span-12">
            <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th width="120" scope="row"><?php echo __('Name'); ?></th>
                    <td>
                        <?php
                        if (!empty($instructor['Account']['label']))
                        {
                            echo $instructor['Account']['label'];
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th width="120" scope="row"><?php echo __('Username'); ?></th>
                    <td>
                        <?php
                        if (!empty($instructor['Account']['username']))
                        {
                            echo $this->Html->link(
                                $instructor['Account']['username'],
                                array(
                                    'plugin' => 'accounts',
                                    'controller' => 'accounts',
                                    'action' => 'view',
                                    $instructor['Account']['id']
                                ),
                                array('class' => 'iconify user')
                            );
                        }
                        else
                        {
                            echo '<span class="blank">None</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th width="120" scope="row"><?php echo __('Approved'); ?></th>
                    <td>
                        <?php
                        if ($instructor['Instructor']['approved'])
                        {
                            echo __('Approved');
                            //cho $instructor['Instructor']['approved'];
                        }
                        else
                        {
                            echo __('Not approved');
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo __('Enabled'); ?></th>
                    <td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $instructor); ?></td>
                </tr>
            </table>
            <h4><?php echo __('Training Instructor Experience'); ?></h4>
            <div class="section_content">
                <table class="vertical_data" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <th width="180" scope="row"><?php echo __('Training Experience'); ?></th>
                        <td>
                        <?php
                            if (!empty($instructor))
                            {
                                echo $instructor['Instructor']['experience'];
                            }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <th width="180" scope="row"><?php echo __('Supporting Documents'); ?></th>
                        <td>
                            <?php
                            if (!empty($instructor['Upload']))
                            {
                                foreach ($instructor['Upload'] as $upload)
                                {
                                    echo $this->Html->link(
                                        __('View Uploaded Documentation'),
                                        $upload['web_path'],
                                        array(
                                            'class' => 'iconify pdf',
                                        )
                                    );
                                    echo '<br />';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="span-5 last">
            <div class="actions">
            <?php echo $this->Html->link(
                __('Edit Information'),
                '/continuing_education/instructors/edit/' . $instructor['Instructor']['id'],
                array('class' => 'button small')
            ); ?>
            </div>
        </div>
    <?php endif ?>
</div>
