<div id="course_locations" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>
    
    <?php if (GenLib::isData($course_locations, '0', array('id'))) : ?>
    
        <div class="actions">
            <?php
                echo $this->Html->link(
                    'Add Course Location',
                    array(
                        'plugin' => 'continuing_education',
                        'controller' => 'course_locations',
                        'action' => 'add',
                        $training_provider['id'],
                        'return' => $return,
                    ),
                    array('class' => 'button small')
                );
            ?>
        </div>

        <table class="light_data" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col">Course Location</th>
                    <th scope="col">Address</th>
                    <th scope="col">Contact Person</th>
                    <th scope="col">Contact Phone</th>
                    <th scope="col">Enabled</th>
                    <th scope="col" width="85">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($course_locations as $location) : ?>
                <tr>
                    <td>
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    $location['Address']['label'], 
                                    array(
                                        'plugin' => 'continuing_education', 
                                        'controller' => 'course_locations', 
                                        'action' => 'edit', 
                                        $location['id'],
                                        'return' => $return,
                                    ), 
                                    array('title' => 'Edit location details')
                                ); 
                            }
                            else
                            {
                                echo $location['Address']['label'];
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $this->element(
                                'templates/standard_address',
                                array(
                                    'address' => $location['Address']
                                ),
                                array(
                                    'plugin' => 'AddressBook'
                                )
                            );
                        ?>
                    </td>
                    <td><?php echo $this->textProcessing->checkForBlank($location['contact_person']); ?></td>
                    <td><?php echo $this->textProcessing->checkForBlank(GenLib::phoneNumber($location['contact_phone'])); ?></td>
                    <td><?php echo $this->Html->enableButton('continuing_education', 'CourseLocations', array('CourseLocation' => $location)); ?></td>
                    <td width="85">
                        <?php
                            if ($app_open)
                            {
                                echo $this->Html->link(
                                    '<i class="icon-remove-sign"></i>&nbsp;Remove', 
                                    array(
                                        'plugin' => 'continuing_education', 
                                        'controller' => 'course_locations', 
                                        'action' => 'delete', 
                                        $location['id'],
                                        'return' => $return,
                                    ), 
                                    array(
                                        'class' => 'inline_action warning',
                                        'title' => 'Remove location',
                                        'escape' => false
                                    ),
                                    __('Are you sure you want to delete the %s location?', $location['Address']['label'])
                                ); 
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    
    <?php else: ?>
    
    <div class="actions text_center">
        <?php 
            echo $this->Html->link(
                'Add Course Location', 
                array(
                    'plugin' => 'continuing_education',
                    'controller' => 'course_locations',
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
