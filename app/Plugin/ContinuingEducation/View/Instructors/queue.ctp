<?php echo $this->element('section_heading'); ?>
<div id="section" class="span-19 last">
	<div class="pad">
		<h2>Pending Course Instructors</h2>
		<hr />
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th scope="col"><?php echo $this->Paginator->sort('Account.last_name', 'Name');?></th>
					<th scope="col"><?php echo $this->Paginator->sort('Program.label', 'Program');?></th>
					<th scope="col"><?php echo $this->Paginator->sort('Instructor.status', 'Status');?></th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($instructors as $instructor): ?>
				<tr>
					<td>
                    <?php
                        echo $this->Html->link(
                            $instructor['Account']['label'],
                            array(
                                'plugin' => 'continuing_education',
                                'controller' => 'instructors',
                                'action' => 'view',
                                $instructor['Instructor']['id'],
                                'return' => base64_encode($this->here),
                            )
                        );
                    ?>
                    </td>
					<td><?php echo ($instructor['Program']['label']!='') ? $instructor['Program']['label'] : '<span class="blank">None</span>'; ?></td>
					<td>##Status##</td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->element('pagination_links'); ?>
	</div>
</div>
