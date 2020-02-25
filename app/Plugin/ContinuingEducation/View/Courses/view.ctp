<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">Course</span> <?php echo h($course['CourseCatalog']['label']); ?></h3>
			<p class="bottom"><?php echo $this->Html->link('< Back to Listing', array('action' => 'index')); ?></p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<div class="actions">
			<?php echo $this->Html->link('Finished', array('action' => 'index'), array('class' => 'button')); ?>
		</div>
		<div id="Course_Information" class="form_section">
			<h3>Course Information</h3>
			<div class="span-12">
				<table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
					<tr>
						<th width="120" scope="row">Title</th>
						<td><?php echo h($course['CourseCatalog']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Description</th>
						<td><?php echo h($course['CourseCatalog']['descr']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Training Provider</th>
						<td><?php echo h($course['TrainingProvider']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Using Training Provider Course Materials?</th>
						<td><?php echo empty($course['Course']['provider_materials']) ? 'No' : 'Yes'; ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Using Training Provider Course Tests?</th>
						<td><?php echo empty($course['Course']['provider_tests']) ? 'No' : 'Yes'; ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Approved</th>
						<td><?php echo empty($course['Course']['approved']) ? 'No' : 'Yes'; ?></td>
					</tr>
                    <?php if ($course['Course']['approved']):  ?>
                        <tr>
						    <th width="120" scope="row">Approved Date</th>
                            <td>
                                    <?php echo $course['Course']['approved_date']; ?>
                            </td>
                        </tr>
                    <?php endif ?>
					<tr>
						<th scope="row">Enabled</th>
						<td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $course); ?></td>
					</tr>
				</table>
			</div>
			<div class="span-5 last">
				<div class="actions">
                <?php 
                   echo $this->Html->link(
                       'Edit Course', 
                       array(
                           'plugin' => 'continuing_education',
                           'controller' => 'courses', 
                           'action' => 'edit', 
                           $course['Course']['id']
                       ), 
                       array('class' => 'button small')
                   );
                ?>
                </div>
			</div>
		</div>
		<div class="actions">
			<?php echo $this->Html->link('Finished', array('action' => 'index'), array('class' => 'button')); ?>
			<?php echo $this->Form->postLink('Delete Course', array('action' => 'delete', $course['Course']['id']), array('class' => 'button delete rightify'), __('Are you sure you want to delete course # %s?', $course['Course']['id'])); ?>
		</div>
	</div>
</div>
