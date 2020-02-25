<div class="courseLocations index">
	<h2><?php echo __('Course Locations');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('address_1');?></th>
			<th><?php echo $this->Paginator->sort('address_2');?></th>
			<th><?php echo $this->Paginator->sort('city');?></th>
			<th><?php echo $this->Paginator->sort('state');?></th>
			<th><?php echo $this->Paginator->sort('zip');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($courseLocations as $courseLocation): ?>
	<tr>
		<td><?php echo h($courseLocation['CourseLocation']['id']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['name']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['address_1']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['address_2']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['city']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['state']); ?>&nbsp;</td>
		<td><?php echo h($courseLocation['CourseLocation']['zip']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $courseLocation['CourseLocation']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $courseLocation['CourseLocation']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $courseLocation['CourseLocation']['id']), null, __('Are you sure you want to delete # %s?', $courseLocation['CourseLocation']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Course Location'), array('action' => 'add')); ?></li>
	</ul>
</div>
