<div id="pre" class="span-5">
	<div id="section_head" class="black-box">
		<h3>Reports</h3>
		<p class="bottom"><?php echo $page_name; ?></p>
	</div>
	<div id="section_nav_holder">
		<ul id="section_nav">
			<? // Links are not complete yet, for show ?>
			<li><?php echo $this->Html->link('Ledger Report', array('plugin' => 'reports', 'controller' => 'reports', 'action' => 'ledger_report')); ?></li>
			<li><?php echo $this->Html->link('Billing Items Report', array('plugin' => 'reports', 'controller' => 'reports', 'action' => 'billing_items_report')); ?></li>
		</ul>
	</div>
</div>