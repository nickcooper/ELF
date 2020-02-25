<?php echo $this->element('section_heading'); ?>
<div id="section" class="span-19 last">
	<div class="pad">
		<h2><?php echo __('Course Catalog'); ?></h2>
		<hr />
		<div class="actions">
			<?php echo $this->Html->link(__('Add Course Catalog Item'), array('action' => 'add'), array('class' => 'button')); ?>
		</div>
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th scope="col"><?php echo $this->Paginator->sort('CourseCatalog.label', 'Course Title');?></th>
					<th scope="col"><?php echo $this->Paginator->sort('Program.label', 'Program');?></th>
                    <th scope="col"><?php echo __('Code Hours'); ?></th>
					<th scope="col"><?php echo __('Non-code Hours'); ?></th>
					<th scope="col"><?php echo __('Test Attempts'); ?></th>
					<th scope="col"><?php echo __('Status'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($course_catalogs as $course): ?>
				<tr>
					<td><?php echo $this->Html->link($course['CourseCatalog']['label'], array('action' => 'view', $course['CourseCatalog']['id'])); ?>&nbsp;</td>
					<td><?php echo h($course['Program']['abbr']); ?></td>
					<td><?php echo h($course['CourseCatalog']['code_hours']); ?></td>
                    <td><?php echo h($course['CourseCatalog']['non_code_hours']); ?></td>
					<td><?php echo h($course['CourseCatalog']['test_attempts']); ?></td>
					<td><?php echo h($course['CourseCatalog']['enabled']); ?></td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->element('pagination_links'); ?>
	</div>
</div>
