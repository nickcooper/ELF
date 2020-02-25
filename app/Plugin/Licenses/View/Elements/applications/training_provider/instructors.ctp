<div id="course_instructors" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo  $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>
    
    <?php if (GenLib::isData($instructors, '0.Account', array('id'))) : ?>
    
    <div class="span-12">
        <table border="0" cellpadding="0" cellspacing="0" class="light_data">
            <thead>
                <tr>
                    <th scope="col">Instructor Account</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $instructor) : ?>
                    <?php if (GenLib::isData($instructor, 'Account', array('id'))) : ?>
                <tr>
                    <td>
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $instructor['Account']['label'],
                                    array(
                                        'plugin' => 'accounts',
                                        'controller' => 'accounts',
                                        'action' => 'view',
                                        $instructor['Account']['id'],
                                        'return' => $return,
                                    )
                                );
                            }
                            else
                            {
                                echo $instructor['Account']['label'];
                            }
                            
                        ?>
                    </td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {  
                                echo $this->Form->postLink(
                                    '<i class="icon-remove-sign"></i>&nbsp;Remove', 
                                    array(
                                        'plugin' => 'continuing_education', 
                                        'controller' => 'instructor_assignments', 
                                        'action' => 'delete', 
                                        $instructor['id'],
                                        'return' => $return,
                                    ), 
                                    array(
                                        'class' => 'inline_action warning',
                                        'title' => 'Remove instructor',
                                        'escape' => false
                                    ), 
                                    __('Are you sure you want to remove # %s?', $instructor['id']), array('title' => 'Remove instructor')
                                ); 
                            }
                        ?>
                    </td>
                </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span-5 last">
        <div class="actions">
            <?php 
                echo $this->Html->link(
                    'Add Instructors', 
                        array(
                            'plugin' => 'continuing_education',
                            'controller' => 'instructor_assignments',
                            'action' => 'add',
                            $training_provider['id'],
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
                'Add Instructors', 
                    array(
                        'plugin' => 'continuing_education',
                        'controller' => 'instructor_assignments',
                        'action' => 'add',
                        $training_provider['id'],
                         'return' => $return,
                     ),
                 array('class' => 'button small')
            ); 
        ?>
    </div>
    
    <?php endif; ?>

</div>
