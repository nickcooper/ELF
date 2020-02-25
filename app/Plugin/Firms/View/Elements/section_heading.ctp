<div id="pre" class="span-5">
    <div id="section_head" class="black-box">
        <h3><?php echo __('Firms'); ?></h3>
        <p class="bottom"><?php echo __('Manage Firms'); ?></p>
    </div>
    <div id="section_nav_holder">
        <ul id="section_nav">
            <li><?php echo $this->Html->link(__('Firms'), array('plugin' => 'firms', 'controller' => 'firms', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Advanced Search'), array('plugin' => 'firms', 'controller' => 'firms', 'action' => 'search')); ?></li>
        </ul>
    </div>
</div>