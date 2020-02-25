<div class="group_program form">
<?php echo $this->Form->create('GroupProgram');?>
    <fieldset>
        <legend><?php echo __('Edit Program Group'); ?></legend>
    <?php
        echo $this->Form->input('GroupProgram.id');
        echo $this->Form->input('GroupProgram.name');
        echo $this->Form->textarea('GroupProgram.descr');
        echo $this->Form->input('GroupProgram.enabled');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Delete', true), array('plugin' => 'accounts', 'controller' => 'programs', 'action' => 'delete', $this->Form->value('GroupProgram.id')), null, sprintf(__('Are you sure you want to delete program group #%s?', true), $this->Form->value('GroupProgram.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Program Groups', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'index'));?></li>
        
        <li><?php echo $this->Html->link(__('List Program Groups', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Program Group', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'add')); ?> </li>
    </ul>
</div>