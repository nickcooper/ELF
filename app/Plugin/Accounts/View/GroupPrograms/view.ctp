<div class="group_programs view">
    <h2><?php echo __('Program Group');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('name'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['label']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Description'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['descr']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Enabled'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['enabled']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['created']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
            <?php echo $group_program['GroupProgram']['modified']; ?>
            &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Edit Program Group', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'edit', $group_program['GroupProgram']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('Delete Program Group', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'delete', $group_program['GroupProgram']['id']), null, sprintf('Are you sure you want to delete program # %s?', $group_program['GroupProgram']['id'])); ?> </li>
        <li><?php echo $this->Html->link(__('List Program Groups', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Program Group', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'add')); ?> </li>
        
        <li><?php //echo $this->Html->link(__('List Program', true), array('plugin' => 'accounts', 'controller' => 'programs', 'action' => 'index')); ?> </li>
        <li><?php //echo $this->Html->link(__('New Program', true), array('plugin' => 'accounts', 'controller' => 'programs', 'action' => 'add')); ?> </li>
    </ul>
</div>
