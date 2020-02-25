<div id="courses_to_teach" class="form_section <?php echo $open_close_class;?>">
    <h3><?php echo $label; ?><?php echo ($required) ? ' <span class="req">Required Section</span>' : ''; ?></h3>

    <?php if (isset($description) && $description['element_plugin'] == 'licenses')
       { echo $description['description'] . '</br></br>'; } ?>

    <?php if (GenLib::isData($license, 'TrainingProvider.Course.0', array('id'))): ?>

    <div class="actions">
        <?php
            echo $this->Html->link(
                'Add Course',
                array(
                    'plugin' => 'continuing_education',
                    'controller' => 'courses',
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
				<th scope="col">Course</th>
                <th scope="col">Materials</th>
                <th scope="col">Tests</th>
                <th scope="col">Approved</th>
                <th scope="col">Enabled</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>
		<tbody>
            <?php foreach($courses as $course) : ?>
			<tr>
				<td>
				    <?php
                        if ($app_open)
                        {
                            echo $this->Html->link(
                                $course['CourseCatalog']['label'],
                                array(
                                    'plugin' => 'continuing_education',
                                    'controller' => 'courses',
                                    'action' => 'edit',
                                    $course['id'],
                                    'return' => $return,
                                ),
                                array('title' => 'Edit course details')
                            );
                        }
                        else
                        {
                            echo $course['CourseCatalog']['label'];
                        }

                    ?>
                    <?php
                        echo $this->Html->link(
                            '<i class="icon-info-sign"></i> <span class="text_status">Info</span>',
                            '#course_catalog_' . $course['CourseCatalog']['id'],
                            array(
                                'class' => 'modal inline_action blue',
                                'title' => 'Course information',
                                'escape' => false
                            )
                        );
                    ?>
                </td>
                <td><?php echo $course['provider_materials'] ? 'Provided' : 'Other'; ?></td>
                <td><?php echo $course['provider_tests'] ? 'Provided' : 'Other'; ?></td>
                <td>
                    <?php echo $this->textProcessing->approvedStatus($course['approved']); ?>
                    <?php echo ($course['approved']) ? genLib::dateFormat($course['approved_date']) : '--'; ?>
                </td>
                <td><?php echo $this->Html->enableButton('continuing_education', 'Courses', array('Course' => $course)); ?></td>
				<td>
				    <?php
                        if ($app_open)
                        {
    				        echo $this->Form->postLink(
    				            '<i class="icon-remove-sign"></i>&nbsp;Remove',
    				            array(
    				                'plugin' => 'continuing_education',
    				                'controller' => 'courses',
    				                'action' => 'delete',
    				                $course['id'],
    				                'return' => $return,
                                ),
    				            array(
                                    'class' => 'inline_action warning',
                                    'escape' => false
                                ),
                                __('Are you sure you want to delete course # %s?', $course['id'])
                            );
                        }
                    ?>
                </td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
    <div id="course_descriptions" class="hide">
    <?php foreach($courses as $course) : ?>
        <div id="<?php echo 'course_catalog_' . $course['CourseCatalog']['id']; ?>" class="course_description">
            <h4><?php echo $course['CourseCatalog']['label']; ?> (<acronym><?php echo $course['CourseCatalog']['abbr']; ?></acronym>)</h4>
            <?php echo $this->textProcessing->pbr($course['CourseCatalog']['descr']); ?>
            <p><strong>Hours:</strong> <?php echo $course['CourseCatalog']['hours']; ?></p>
        </div>
    <?php endforeach; ?>
    </div>
    <?php else: ?>

    <div class="actions text_center">
    	<?php
    	    echo $this->Html->link(
    	        'Add Course',
    	        array(
                    'plugin' => 'continuing_education',
                    'controller' => 'courses',
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