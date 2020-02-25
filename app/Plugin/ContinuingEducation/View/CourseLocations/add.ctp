<div id="pre" class="span-5">
    <div class="stationary">
        <div id="section_head" class="black-box">
            <h3>
                <span class="pre"><?php echo Inflector::humanize(Inflector::underscore($license['License']['foreign_obj'])) . ' #'; ?></span>
                <?php echo $license['License']['license_number']; ?>
            </h3>
            <p class="bottom">
                <?php echo $this->IiHtml->returnLink(); ?>
            </p>
        </div>
    </div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<?php echo $this->Form->create('CourseLocation');?>
		<h3>Add Course Location</h3>
		<?php echo $this->element('form_course_location'); ?>
		<?php echo $this->Form->end('Save');?>
	</div>
</div>