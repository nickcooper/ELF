<?php echo $this->element('section_heading', array(), array('plugin' => 'reports')); ?>

<div id="section" class="span-19 last">
	<div class="pad">
		<h2><?php echo $page_name; ?></h2>
		<hr/>
		<ul>
			<li><?php echo $this->Html->link('Ledger Report', array('plugin' => 'reports', 'controller' => 'reports', 'action' => 'ledger_report')); ?></li>
			<li><?php echo $this->Html->link('Billing Items Report', array('plugin' => 'reports', 'controller' => 'reports', 'action' => 'billing_items_report')); ?></li>
		</ul>
	</div>
</div>
