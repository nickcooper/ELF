<?php echo $this->element('section_heading', array(), array('plugin' => 'output_documents')); ?>

<div id="section" class="span-19 last">
	<div class="pad">
		<h2><?php echo $label; ?> Documents</h2>
		<hr/>
		
		<!-- unbatched records -->
        <?php if ($queue_count > 0) : ?>
            <div class="notice">
                There are <strong><?php echo sprintf('%s %s', $queue_count, Inflector::pluralize($label)); ?></strong> that need to be batched. Would you like to process these files now? 
                <?php 
                    echo $this->Html->link(
                        'Yes', 
                        array(
                            'plugin' => 'output_documents',
                            'controller' => 'output_documents',
                            'action' => 'batchItems',
                            $slug
                        )
                    ); 
                ?>
            </div>
        <?php endif; ?>
        
		<table class="data" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>Batched Date</th>
					<th>Count</th>
					<th>Download</th>
				</tr>
			</thead>
			<tbody>
                <?php if (!count($batches)) : ?>
                    <tr>
                        <td colspan="3"><p>There aren't any batches to be downloaded.</p></td>
                    </tr>
                <?php else : ?>
                    <!-- batch records -->
    				<?php foreach($batches as $batch): ?>
    				<tr>
    					<td><?php echo $batch['batch_date']; ?></td>
    					<td><?php echo $batch['count']; ?></td>
    					<td>
					    <?php foreach ($docs_config[preg_replace('/-/', '_', $slug)]['types'] as $type => $data) : ?>
                            <?php 
                                echo $this->Html->link(
                                    strtoupper($type), 
                                    array(
                                        'plugin' => 'output_documents',
                                        'controller' => 'output_documents',
                                        'action' => 'downloadBatch',
                                        $batch['id'],
                                        sprintf('%s_%s_%s', $slug, date('Y-m-d', strtotime($batch['batch_date'])), time()),
                                        'ext' => $type
                                    )
                                );
                            ?> &nbsp;&nbsp;
                        <?php endforeach; ?>
    			        </td>
    			    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
		</table>
	</div>
</div>
