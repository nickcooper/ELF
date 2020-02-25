<div class="group_programs form">
<?php echo $this->Form->create('GroupProgram');?>
    <fieldset>
        <legend><?php __('Add Program Group'); ?></legend>
    <?php
        echo $this->Form->input('GroupProgram.name');
        echo $this->Form->textarea('GroupProgram.descr');
        echo $this->Form->input('GroupProgram.enabled');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Program Groups', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'index'));?></li>
    </ul>
</div>