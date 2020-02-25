<div class="group_programs index">
    <h2><?php __('Program Groups');?></h2>
    <table cellpadding="0" cellspacing="0">
    <tr>
            <th><?php echo $this->Paginator->sort('GroupProgram.id', 'Id');?></th>
            <th><?php echo $this->Paginator->sort('GroupProgram.name', 'Group Name');?></th>
            <th><?php echo $this->Paginator->sort('GroupProgram.enabled', 'Active');?></th>
            <th><?php echo $this->Paginator->sort('GroupProgram.modified', 'Modified');?></th>
            <th class="actions"><?php __('Actions');?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($group_programs as $group_program):
        $class = null;
        if ($i++ % 2 == 0) 
        {
            $class = ' class="altrow"';
        }
    ?>
    <tr<?php echo $class;?>>
        <td><?php echo $group_program['GroupProgram']['id']; ?>&nbsp;</td>
        <td><?php echo $group_program['GroupProgram']['label']; ?>&nbsp;</td>
        <td><?php echo $group_program['GroupProgram']['enabled']; ?>&nbsp;</td>
        <td><?php echo $group_program['GroupProgram']['modified']; ?>&nbsp;</td>
        <td class="actions">
            <?php echo $this->Html->link(__('View', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'view', $group_program['GroupProgram']['id'])); ?>
            <?php echo $this->Html->link(__('Edit', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'edit', $group_program['GroupProgram']['id'])); ?>
            <?php echo $this->Html->link(__('Delete', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'delete', $group_program['GroupProgram']['id']), null, sprintf('Are you sure you want to delete  program group # %s?', $group_program['GroupProgram']['id'])); ?>
        </td>
    </tr>
<?php endforeach; ?>
    </table>
    <p>
    <?php echo $this->Paginator->counter(
        'Page {:page} of {:pages}, showing {:current} records out of
        {:count} total, starting on record {:start}, ending on {:end}'
    ); ?>
    </p>

    <div class="paging">
        <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
        |  <?php echo $this->Paginator->numbers();?>
        |  <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
    </div>
</div>
<div class="actions">
    <h3><?php __('Actions'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('New Program Group', true), array('plugin' => 'accounts', 'controller' => 'group_programs', 'action' => 'add')); ?></li>
        <li><?php echo $this->Html->link(__('List Programs', true), array('plugin' => 'accounts', 'controller' => 'programs', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Program', true), array('plugin' => 'accounts', 'controller' => 'programs', 'action' => 'add')); ?> </li>
    </ul>
</div>