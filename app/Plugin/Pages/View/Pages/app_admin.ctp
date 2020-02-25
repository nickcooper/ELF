<div id="main-content" class="span-18">
<?php foreach ($action_links as $i => $link): ?>
	<div class="span-6<?php echo ($i % 3 == 2) ? ' last' : ''; ?>">
		<div class="cat_box">
			<h3><?php echo $this->Html->link($link['label'], $link['path']); ?></h3>
			<p class="bottom"><?php echo $link['descr']; ?>
			<span class="go_light">&nbsp;</span>
		</div>
	</div>
<?php endforeach; ?>
</div>
<div id="sidebar" class="span-6 last">
	<div class="black-box">
		<div class="widget module">
			<h3><?php echo __('Shortcuts'); ?></h3>
		</div>
		<div class="widget module">
			<h3><?php echo __('Recent Activity'); ?></h3>
		</div>
	</div>
</div>