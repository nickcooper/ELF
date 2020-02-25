<?php echo $this->element('section_nav'); ?>
<div id="section" class="span-19 last">
	<div class="pad">
		<div class="actions">
			<?php echo $this->Html->link('Finished', array('plugin' => 'accounts', 'controller' => 'accounts', 'action' => 'index'), array('class'=>'button')); ?>
		</div>
<div id="notes" class="form_section">
	<h3>Notes</h3>
	<div class="span-12">
		<?php if (!empty($notes)) : ?>
		<ul class="notes_list">
			<?php foreach ($notes as $note): ?>
			<li>
				<span class="note_body"><?php echo $this->TextProcessing->pbr($note['Note']['note']); ?></span>
				<ul>
					<li class="timestamp"><?php echo $this->TextProcessing->formatDate($note['Note']['created'], true); ?></li>
					<li class="author"><?php
						echo $this->Html->link(
							sprintf('<span class="name">%s</span><span class="username">%s</span>', 
								$note['Account']['first_name'] . ' ' . $note['Account']['last_name'], 
								$note['Account']['username']
							),
							'mailto:' . $note['Account']['email'],
							array('escape' => false)
						);
					?></li>
				</ul>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php else: ?>
		<div class="notice">There are no notes.</div>
		<?php endif; ?>
	</div>
	<div class="span-5 last">
		<div class="actions">
		<?php echo $this->Html->link(
	        'Add Note',
	        array(
	            'plugin' => 'notes',
	            'controller' => 'notes',
	            'action' => 'add',
	            'fp' => $foreign_plugin,
	            'fo' => $foreign_obj,
	            'fk' => $foreign_key, 
	            'return' => base64_encode($this->here)
	        ),
	        array(
	        	'class' => 'button small'
	        )
	    );
	    ?>
		</div>
	</div>
</div>
<div class="actions">
			<?php echo $this->Html->link('Finished', array('plugin' => 'accounts', 'controller' => 'accounts', 'action' => 'index'), array('class'=>'button')); ?>
		</div>