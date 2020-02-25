<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre"><?php echo __('View'); ?></span> <?php echo __('Course Section'); ?></h3>
			<p class="bottom"><?php echo $this->Html->link(__('< Back to listing'), array('action' => 'index')); ?></p>
		</div>
		<div id="section_nav_holder">
			<ul id="section_nav">
				<li><a href="#"></a></li>
			</ul>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<div class="actions">
			<?php echo $this->Html->link(__('Finished'), array('action' => 'index'), array('class' => 'button')); ?>
		</div>
		<div id="Course_Information" class="form_section">
			<h3><?php echo __('Course Information'); ?></h3>
			<div class="span-12">
				<table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
					<tr>
						<th width="120" scope="row"><?php echo __('Training Provider'); ?></th>
						<td><?php echo h($courseSection['TrainingProvider']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row"><?php echo __('Course Type'); ?></th>
						<td><?php echo h($courseSection['CourseSection']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row"><?php echo __('Course Section Number'); ?></th>
						<td><?php echo h($courseSection['CourseSection']['course_section_number']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row"><?php echo __('Course Instructor'); ?></th>
						<td><?php echo h($courseSection['Account']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row"><?php echo __('Course Location'); ?></th>
						<td><?php echo h($courseSection['Address']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row"><?php echo __('Course Dates'); ?></th>
						<td>
							<?php echo h(GenLib::dateFormat($courseSection['CourseSection']['start_date'])); ?> &ndash;
							<?php echo h(GenLib::dateFormat($courseSection['CourseSection']['end_date'])); ?>
						</td>
					</tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Code Hours'); ?></th>
                        <td><?php echo h($courseSection['CourseCatalog']['code_hours']); ?></td>
                    </tr>
                    <tr>
                        <th width="120" scope="row"><?php echo __('Non-code Hours'); ?></th>
                        <td><?php echo h($courseSection['CourseCatalog']['non_code_hours']); ?></td>
                    </tr>
					<tr>
                        <th width="120" scope="row"><?php echo __('Enabled'); ?></th>
                        <td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $courseSection); ?></td>
                    </tr>
				</table>
			</div>
			<div class="span-5 last">
				<div class="actions">
                    <?php
                    	echo $this->Html->link(
	                        __('Edit Course Info'),
	                        array(
	                            'controller' => 'course_sections',
	                            'action'     => 'edit',
	                            $courseSection['CourseSection']['id'],
	                            'return'     => base64_encode($this->here),
	                        ),
	                        array('class' => 'button small')
                    	);
                	?>
                </div>
			</div>
		</div>
		<div id="Course_Roster" class="form_section">
			<h3><?php echo __('Course Roster'); ?></h3>
			<div class="actions">
				<?php
					echo $this->Html->link(
	                    __('Add Student'),
	                    array(
	                        'controller' => 'course_rosters',
	                        'action'     => 'add',
	                        $courseSection['CourseSection']['id'],
	                        'return'     => base64_encode($this->here),
	                    ),
	                    array('class' => 'button small')
                	);
            	?>
				<?php
					echo $this->Html->link(
						__('Export Roster'),
						array(
							'controller' => 'course_rosters',
							'action'     => 'export',
							sprintf('%s.csv', $courseSection['CourseSection']['course_section_number']),
						),
						array('class' => 'button cancel small')
					);
				?>
			</div>
			<table class="light_data" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th scope="col"><?php echo __('Name'); ?></th>
						<th scope="col"><?php echo __('Completed'); ?></th>
						<th scope="col"><?php echo __('Test Result'); ?></th>
						<th scope="col"><?php echo __('Test Date'); ?></th>
						<th scope="col"><?php echo __('Test Attempt'); ?></th>
					</tr>
				</thead>
				<tbody>
<?php foreach($courseSection['CourseRoster'] as $roster): ?>
					<tr>
						<td>
							<?php
								echo $this->Html->link(
									$roster['Account']['label'],
									array(
										'controller' => 'course_rosters',
										'action'     => 'edit',
										$roster['id'],
									)
								);
							?>
						</td>
						<td><?php echo $roster['completed'] ? __('Completed') : __('Not completed'); ?></td>
						<?php
						// figure out which test data to show
						if (! empty($roster['ExamScore']))
						{
							// test HAS been taken
							$test = $roster['ExamScore'][0]; // data is ordered by date, descending so zero key is latest exam
							$pass = $test['pass'] ? __('Pass') : __('Fail');
							$testDate = GenLib::dateFormat($test['exam_date']);
							$testAttempt = count($roster['ExamScore']);
						}
						else
						{
							// test HAS NOT been taken
							$pass = __('not taken');
							$testDate = 'N/A';
							$testAttempt = 'N/A';
						}

						?>
						<td><?php echo h($pass); ?></td>
						<td><?php echo h($testDate); ?></td>
						<td><?php echo h($testAttempt); ?></td>
					</tr>
<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div class="actions">
		<?php
			echo $this->Html->link(__('Finished'), array('action' => 'index'), array('class' => 'button'));
			echo $this->Form->postLink(
				__('Delete Course Section'),
				array(
					'action' => 'delete',
					$courseSection['CourseSection']['id'],
				),
				array('class' => 'button delete rightify'),
				sprintf(__('Are you sure you want to delete Course Section "%s"?'), $courseSection['CourseSection']['label'])
			);
		?>
		</div>
	</div>
</div>
