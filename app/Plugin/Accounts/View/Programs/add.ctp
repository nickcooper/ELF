<div id="pre" class="span-5">
	<div class="stationary">
		<div id="section_head" class="black-box">
			<h3><span class="pre"><?php echo __('New'); ?></span> <?php echo __('Program'); ?></h3>
			<p class="bottom">
				<?php
					echo $this->Html->link(
						__('< Back to Program Listing'),
						array('controller' => 'programs', 'action' => 'index')
					);
				?>
			</p>
		</div>
	</div>
</div>
<div id="section" class="span-19 last">
	<div class="pad">
		<h3><?php echo __('Add Program Information'); ?></h3>
		<?php echo $this->element('program_form'); ?>
	</div>
</div>