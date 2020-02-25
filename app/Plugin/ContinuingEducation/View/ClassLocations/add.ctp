<div class="courseLocations form">
<?php echo $this->Form->create('CourseLocation');?>
	<fieldset>
		<legend><?php echo __('Add Course Location'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('address_1');
		echo $this->Form->input('address_2');
		echo $this->Form->input('city');
		echo $this->Form->input('state');
		echo $this->Form->input('zip');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Course Locations'), array('action' => 'index'));?></li>
	</ul>
</div>
