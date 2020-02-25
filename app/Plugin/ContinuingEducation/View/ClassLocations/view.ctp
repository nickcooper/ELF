<div class="courseLocations view">
<h2><?php  echo __('Course Location');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address 1'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['address_1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address 2'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['address_2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['city']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('State'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['state']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Zip'); ?></dt>
		<dd>
			<?php echo h($courseLocation['CourseLocation']['zip']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Course Location'), array('action' => 'edit', $courseLocation['CourseLocation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Course Location'), array('action' => 'delete', $courseLocation['CourseLocation']['id']), null, __('Are you sure you want to delete # %s?', $courseLocation['CourseLocation']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Course Locations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course Location'), array('action' => 'add')); ?> </li>
	</ul>
</div>
