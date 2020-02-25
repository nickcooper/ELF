<?php
$program = $this->request->data['Program'];
?>
<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre"><?php echo __('Program'); ?></span> <?php echo h($program['label']); ?></h3>
			<p class="bottom">
				<?php
					echo $this->Html->link(
						__('< Back to Program Overview'),
						array('controller' => 'programs', 'action' => 'view', $program['id'])
					);
				?>
			</p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<h3><?php echo __('Edit Program Information'); ?></h3>
		<?php echo $this->element('program_form'); ?>
	</div>
</div>