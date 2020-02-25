<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo Inflector::humanize(Inflector::underscore($course_location['Address']['foreign_obj'])); ?></span>
                <?php echo $course_location['Address']['label']; ?>
            </h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<div class="actions">
			<?php echo $this->Html->link('Finished', array('action' => 'index'), array('class' => 'button')) ?>
		</div>
		<div id="Location" class="form_section">
			<h3>Location</h3>
			<div class="span-12">
				<table border="0" cellpadding="0" cellspacing="0" class="vertical_data">
					<tr>
						<th width="120" scope="row">Name</th>
						<td><?php echo h($course_location['Address']['label']); ?></td>
					</tr>
					<tr>
						<th width="120" scope="row">Address</th>
						<td>
							<?php echo h($course_location['Address']['addr1']); ?><br/>
							<?php echo ($course_location['Address']['addr2']!='') ? h($course_location['Address']['addr2']) . '<br/>' : ''; ?>
							<?php echo h($course_location['Address']['city']); ?> <?php echo h($course_location['Address']['state']); ?>, <?php echo h($course_location['Address']['postal']); ?>
						</td>
						<tr>
                        <th width="120" scope="row">Enabled</th>
	                        <td><?php echo $this->Html->enableButton($this->params['plugin'], $this->params['controller'], $course_location); ?></td>
	                    </tr>
					</tr>
				</table>
			</div>
			<div class="span-5 last">
				<div class="actions"><?php echo $this->Html->link('Edit Location', array('action' => 'edit', $course_location['CourseLocation']['id']), array('class' => 'button small')); ?></div>
			</div>
		</div>
		<div class="append-bottom"><?php echo $this->Form->postLink(__('Delete Course Location'), array('action' => 'delete', $course_location['CourseLocation']['id']), array('class' => 'iconify warning'), __('Are you sure you want to delete # %s?', $course_location['CourseLocation']['id'])); ?></div>
		<div class="actions">
			<?php echo $this->Html->link('Finished', array('action' => 'index'), array('class' => 'button')) ?>
		</div>
	</div>
</div>
