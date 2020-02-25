<?php echo $this->element('section_heading', array(), array('plugin' => 'licenses')); ?>

<div id="section" class="span-19 last">
	<div class="pad">
		<h2>Licenses</h2>
		<hr/>
        
		<table class="data" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo $this->Paginator->sort('License.license_number', 'License Number');?></th>
                    <th><?php echo $this->Paginator->sort(sprintf('%s.label', '$foreign_obj'), 'License Holder');?></th>
                    <th><?php echo $this->Paginator->sort('LicenseType.type', 'License Type');?></th>
                    <th><?php echo $this->Paginator->sort('LicenseStatus.status', 'Status');?></th>
                    <th><?php echo $this->Paginator->sort('License.expire_date', 'Date Expires');?></th>
					<th><?php echo $this->Paginator->sort('License.modified', 'Last Modified');?></th>
				</tr>
			</thead>
			<tbody>
<?php
		foreach ($licenses as $license):
?>
				<tr>
			        <td>
			            <?php echo $this->Html->link(
                            ( $license['License']['license_number'] !='' ? $license['License']['license_number'] : 'N/A' ), 
                            array(
                                'plugin' => 'licenses', 
                                'controller' => 'licenses', 
                                'action' => 'application', 
                                $license['License']['id'],
                                'return' => base64_encode($this->here)
                            )
                        ); ?>
                        &nbsp;
                    </td>
			        <td><?php echo $license[$license['License']['foreign_obj']]['label']; ?></td>
                    <td><?php echo $license['LicenseType']['label']; ?></td>
                    <td><?php echo $license['LicenseStatus']['status']; ?></td>
                    <td><?php echo $license['License']['expire_date']; ?></td>
			        <td><?php echo $license['License']['modified']; ?></td>
			    </tr>
<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->element('pagination_links'); ?>
	</div>
</div>
