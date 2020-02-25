<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">Edit</span> Course Section</h3>
			<p class="bottom"><?php echo $this->Html->link('< Back to Listing', array('action' => 'index'));?></p>
		</div>
	</div>        
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<?php echo $this->Form->create('CourseSection');?>
		<h3>Edit Course Section</h3>
		<fieldset>
			<legend>Course Information</legend>
			<?php echo $this->Form->hidden('id'); ?>
            <?php if ($display_course_title_only): ?>
                <p><?php echo $course_title ?></p>
			    <?php echo $this->Form->hidden('course_catalog_id'); ?>
            <?php else: ?>
			    <?php echo $this->Form->input('course_catalog_id', array('options' => $courses, 'label' => 'Course')); ?>
            <?php endif ?>
			<?php echo $this->Form->input('address_id', array('options' => $course_locations, 'label' => 'Course Location')); ?>
                <?php echo $this->Form->input('start_date', array('div' => array('class' => 'form_item span-8'), 'interval' => '15')); ?>
                <?php echo $this->Form->input('end_date', array('div' => array('class' => 'form_item span-10 last'), 'interval' => '15')); ?>
		</fieldset>
 
		<fieldset>
			<legend>Course Instruction</legend>
			<?php echo $this->Form->input('account_id', array('options' => $instructors, 'label' => 'Instructor')); ?>
		</fieldset>

		<?php echo $this->Form->end('Save');?>
	</div>
</div>
