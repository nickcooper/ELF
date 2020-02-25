<div class="firmTypes form">
<?php echo $this->Form->create('FirmType');?>
    <fieldset>
        <legend><?php echo __('Edit Firm Type'); ?></legend>
    <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('FirmType.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('FirmType.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Firm Types'), array('action' => 'index'));?></li>
        <li><?php echo $this->Html->link(__('List Firms'), array('controller' => 'firms', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Firm'), array('controller' => 'firms', 'action' => 'add')); ?> </li>
    </ul>
</div>
