<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre">Edit</span> ##Instructor##</h3>
			<p class="bottom"><?php echo $this->Html->link(__('< Back to Listing'), array('action' => 'index'));?></p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<h3><?php echo __('Edit Instructor'); ?></h3>
		<?php echo $this->Form->create('Instructor', array('enctype' => 'multipart/form-data'));?>
        <?php echo $this->Form->hidden('Instructor.id');?>
		<fieldset>
			<legend><?php echo __('Instructor Information'); ?></legend>
            <p><?php echo $this->data['Account']['label']; ?></p>
		</fieldset>
		<fieldset>
			<legend><?php echo __('Training Experience'); ?></legend>
            <?php
            echo $this->Form->input(
                'Instructor.experience', array(
                    'label' => 'Training Experience',
                )
            );
            echo $this->element(
                'upload', array(
                    'label' => 'Supporting Documents',
                    'name' => 'image',
                    'type' => 'file',
                ),
                array('plugin' => 'Uploads')
            );
            ?>
		</fieldset>
	<?php echo $this->Form->end('Save');?>
	</div>
</div>
